/**
 * Amazon Review Worker Server
 *
 * This server runs on the static PC and:
 * 1. Receives task commands from cPanel via HTTP POST
 * 2. Executes Puppeteer scripts to automate Amazon reviews
 * 3. Reports progress back to cPanel API
 * 4. Sends periodic heartbeats to show it's online
 */

const express = require('express');
const { exec } = require('child_process');
const axios = require('axios');
const fs = require('fs');
const path = require('path');
require('dotenv').config();

const app = express();
app.use(express.json());

// Worker Configuration
const WORKER_ID = process.env.WORKER_ID || 'PC-1';
const WORKER_PORT = process.env.WORKER_PORT || 3000;
const CPANEL_API = process.env.CPANEL_API_URL || 'https://yourdomain.com/api/worker';
const API_TOKEN = process.env.CPANEL_API_TOKEN || '';
const HEARTBEAT_INTERVAL = parseInt(process.env.HEARTBEAT_INTERVAL) || 60000; // 1 minute

// Worker State
let isRunningTask = false;
let currentTaskId = null;

// Ensure required directories exist
const ensureDirectories = () => {
    const dirs = ['./tasks', './chrome-data', './logs', './screenshots'];
    dirs.forEach(dir => {
        if (!fs.existsSync(dir)) {
            fs.mkdirSync(dir, { recursive: true });
        }
    });
};

/**
 * Main endpoint to receive tasks from cPanel
 */
app.post('/execute', async (req, res) => {
    const {
        task_id,
        review_id,
        account_id,
        book_asin,
        review_title,
        review_description,
        rating
    } = req.body;

    console.log(`\n[${new Date().toISOString()}] Received task request:`, {
        task_id,
        review_id,
        account_id,
        book_asin
    });

    // Check if already running a task
    if (isRunningTask) {
        console.log(`[${new Date().toISOString()}] Worker is busy with task ${currentTaskId}`);
        return res.status(503).json({
            success: false,
            message: 'Worker is busy with another task',
            current_task: currentTaskId,
            worker_id: WORKER_ID
        });
    }

    // Validate required fields
    if (!task_id || !review_id) {
        return res.status(400).json({
            success: false,
            message: 'Missing required fields: task_id, review_id'
        });
    }

    // Mark as busy
    isRunningTask = true;
    currentTaskId = task_id;

    // Return immediate response
    res.json({
        success: true,
        message: 'Task started',
        worker_id: WORKER_ID,
        task_id: task_id
    });

    // Execute task in background
    console.log(`[${new Date().toISOString()}] Starting task execution in background...`);
    executeTask({
        task_id,
        review_id,
        account_id,
        book_asin,
        review_title,
        review_description,
        rating
    });
});

/**
 * Execute the Puppeteer script
 */
async function executeTask(taskData) {
    try {
        console.log(`[${new Date().toISOString()}] Executing task ${taskData.task_id}...`);

        // Save task data to file for the Puppeteer script to read
        const taskFilePath = path.join(__dirname, 'tasks', `${taskData.task_id}.json`);
        fs.writeFileSync(taskFilePath, JSON.stringify(taskData, null, 2));

        // Run Puppeteer script
        const scriptPath = path.join(__dirname, 'scripts', 'submit-review.js');
        const command = `node "${scriptPath}" ${taskData.task_id}`;

        console.log(`[${new Date().toISOString()}] Running command: ${command}`);

        exec(command, async (error, stdout, stderr) => {
            if (error) {
                console.error(`[${new Date().toISOString()}] Task ${taskData.task_id} failed:`, error.message);
                await reportToAPI(taskData.task_id, 'failed', error.message);
            } else {
                console.log(`[${new Date().toISOString()}] Task ${taskData.task_id} completed:`, stdout);
            }

            // Clean up task file
            try {
                if (fs.existsSync(taskFilePath)) {
                    fs.unlinkSync(taskFilePath);
                }
            } catch (cleanupError) {
                console.error('Failed to clean up task file:', cleanupError);
            }

            // Mark as free
            isRunningTask = false;
            currentTaskId = null;
            console.log(`[${new Date().toISOString()}] Worker is now FREE`);
        });

    } catch (error) {
        console.error(`[${new Date().toISOString()}] Execute task error:`, error);
        isRunningTask = false;
        currentTaskId = null;
        await reportToAPI(taskData.task_id, 'failed', error.message);
    }
}

/**
 * Report task completion/failure back to cPanel API
 */
async function reportToAPI(taskId, status, message) {
    try {
        const url = `${CPANEL_API}/tasks/${taskId}/complete`;
        console.log(`[${new Date().toISOString()}] Reporting to API: ${url}`);

        await axios.post(url, {
            status: status,
            message: message,
            worker_id: WORKER_ID
        }, {
            headers: {
                'Authorization': `Bearer ${API_TOKEN}`,
                'Content-Type': 'application/json'
            }
        });

        console.log(`[${new Date().toISOString()}] Successfully reported task ${taskId} status: ${status}`);
    } catch (error) {
        console.error(`[${new Date().toISOString()}] Failed to report to API:`, error.message);
    }
}

/**
 * Health check endpoint
 */
app.get('/health', (req, res) => {
    res.json({
        success: true,
        status: isRunningTask ? 'busy' : 'free',
        worker_id: WORKER_ID,
        current_task: currentTaskId,
        uptime: process.uptime(),
        timestamp: new Date().toISOString()
    });
});

/**
 * Get worker status
 */
app.get('/status', (req, res) => {
    res.json({
        success: true,
        worker_id: WORKER_ID,
        status: isRunningTask ? 'busy' : 'online',
        current_task_id: currentTaskId,
        uptime: process.uptime()
    });
});

/**
 * Send periodic heartbeat to cPanel
 */
async function sendHeartbeat() {
    try {
        const url = `${CPANEL_API}/heartbeat`;

        await axios.post(url, {
            worker_id: WORKER_ID,
            status: isRunningTask ? 'busy' : 'online',
            current_task_id: currentTaskId,
            chrome_version: 'Chrome/120.0.0.0' // You can get this dynamically if needed
        }, {
            headers: {
                'Authorization': `Bearer ${API_TOKEN}`,
                'Content-Type': 'application/json'
            }
        });

        console.log(`[${new Date().toISOString()}] Heartbeat sent successfully`);
    } catch (error) {
        console.error(`[${new Date().toISOString()}] Failed to send heartbeat:`, error.message);
    }
}

/**
 * Start the server
 */
ensureDirectories();

app.listen(WORKER_PORT, () => {
    console.log('='.repeat(60));
    console.log(`Amazon Review Worker Server`);
    console.log('='.repeat(60));
    console.log(`Worker ID: ${WORKER_ID}`);
    console.log(`Server running on: http://localhost:${WORKER_PORT}`);
    console.log(`Status: FREE (Ready to receive tasks)`);
    console.log(`cPanel API: ${CPANEL_API}`);
    console.log('='.repeat(60));

    // Send initial heartbeat
    sendHeartbeat();

    // Set up periodic heartbeat
    setInterval(sendHeartbeat, HEARTBEAT_INTERVAL);
});

// Graceful shutdown
process.on('SIGINT', () => {
    console.log('\n\nShutting down worker server...');
    process.exit(0);
});

process.on('SIGTERM', () => {
    console.log('\n\nShutting down worker server...');
    process.exit(0);
});
