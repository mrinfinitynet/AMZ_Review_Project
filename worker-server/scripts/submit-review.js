/**
 * Puppeteer Script for Submitting Amazon Reviews
 *
 * This script:
 * 1. Reads task data from JSON file
 * 2. Opens Chrome with cached sessions
 * 3. Navigates to Amazon product page
 * 4. Submits the review
 * 5. Reports progress to cPanel API at each step
 */

const puppeteer = require('puppeteer');
const fs = require('fs');
const path = require('path');
const axios = require('axios');
require('dotenv').config({ path: path.join(__dirname, '..', '.env') });

const CPANEL_API = process.env.CPANEL_API_URL || 'https://yourdomain.com/api/worker';
const API_TOKEN = process.env.CPANEL_API_TOKEN || '';
const WORKER_ID = process.env.WORKER_ID || 'PC-1';
const CHROME_USER_DATA_DIR = process.env.CHROME_USER_DATA_DIR || './chrome-data';
const CHROME_HEADLESS = process.env.CHROME_HEADLESS === 'true';

/**
 * Update task progress
 */
async function updateProgress(taskId, progress, message) {
    try {
        const url = `${CPANEL_API}/tasks/${taskId}/progress`;
        await axios.post(url, {
            progress: progress,
            message: message,
            worker_id: WORKER_ID
        }, {
            headers: {
                'Authorization': `Bearer ${API_TOKEN}`,
                'Content-Type': 'application/json'
            },
            timeout: 5000
        });

        console.log(`[${new Date().toISOString()}] Progress: ${progress}% - ${message}`);
    } catch (error) {
        console.error(`[${new Date().toISOString()}] Failed to update progress:`, error.message);
    }
}

/**
 * Report task completion
 */
async function reportCompletion(taskId, status, message, screenshotPath = null) {
    try {
        const url = `${CPANEL_API}/tasks/${taskId}/complete`;
        await axios.post(url, {
            status: status,
            message: message,
            worker_id: WORKER_ID,
            screenshot_path: screenshotPath
        }, {
            headers: {
                'Authorization': `Bearer ${API_TOKEN}`,
                'Content-Type': 'application/json'
            },
            timeout: 5000
        });

        console.log(`[${new Date().toISOString()}] Task completed: ${status} - ${message}`);
    } catch (error) {
        console.error(`[${new Date().toISOString()}] Failed to report completion:`, error.message);
    }
}

/**
 * Take screenshot
 */
async function takeScreenshot(page, taskId, name) {
    try {
        const screenshotDir = path.join(__dirname, '..', 'screenshots');
        if (!fs.existsSync(screenshotDir)) {
            fs.mkdirSync(screenshotDir, { recursive: true });
        }

        const screenshotPath = path.join(screenshotDir, `task-${taskId}-${name}-${Date.now()}.png`);
        await page.screenshot({ path: screenshotPath, fullPage: false });
        console.log(`[${new Date().toISOString()}] Screenshot saved: ${screenshotPath}`);
        return screenshotPath;
    } catch (error) {
        console.error(`[${new Date().toISOString()}] Failed to take screenshot:`, error.message);
        return null;
    }
}

/**
 * Main function to submit review
 */
async function submitReview(taskId) {
    let browser;

    try {
        // Load task data
        const taskFilePath = path.join(__dirname, '..', 'tasks', `${taskId}.json`);
        if (!fs.existsSync(taskFilePath)) {
            throw new Error(`Task file not found: ${taskFilePath}`);
        }

        const taskData = JSON.parse(fs.readFileSync(taskFilePath, 'utf8'));
        console.log(`[${new Date().toISOString()}] Task data loaded:`, {
            task_id: taskData.task_id,
            review_id: taskData.review_id,
            book_asin: taskData.book_asin
        });

        // Progress: 0%
        await updateProgress(taskId, 0, 'Starting Chrome browser');

        // Launch browser with cache
        const userDataDir = path.resolve(CHROME_USER_DATA_DIR);
        console.log(`[${new Date().toISOString()}] Chrome user data dir: ${userDataDir}`);

        browser = await puppeteer.launch({
            headless: CHROME_HEADLESS,
            userDataDir: userDataDir,
            args: [
                '--no-sandbox',
                '--disable-setuid-sandbox',
                '--disable-dev-shm-usage',
                '--disable-blink-features=AutomationControlled'
            ],
            defaultViewport: {
                width: 1280,
                height: 800
            }
        });

        const page = await browser.newPage();

        // Progress: 20%
        await updateProgress(taskId, 20, 'Chrome opened, navigating to Amazon');

        // Navigate to Amazon product page
        const amazonUrl = `https://www.amazon.com/dp/${taskData.book_asin}`;
        console.log(`[${new Date().toISOString()}] Navigating to: ${amazonUrl}`);

        await page.goto(amazonUrl, {
            waitUntil: 'networkidle2',
            timeout: 30000
        });

        await page.waitForTimeout(2000);
        await takeScreenshot(page, taskId, 'product-page');

        // Progress: 40%
        await updateProgress(taskId, 40, 'On product page, looking for review button');

        // ==========================================
        // YOUR EXISTING REVIEW SUBMISSION LOGIC HERE
        // ==========================================
        // This is where you would add your specific logic to:
        // 1. Click on "Write a review" button
        // 2. Fill in the review form
        // 3. Submit the review
        // 4. Verify submission
        //
        // Example placeholder:
        // const writeReviewBtn = await page.$('a[data-hook="write-review-button"]');
        // if (writeReviewBtn) {
        //     await writeReviewBtn.click();
        //     await page.waitForTimeout(2000);
        // }

        // Progress: 60%
        await updateProgress(taskId, 60, 'Filling review form');

        // Simulate review submission (replace with your actual logic)
        await page.waitForTimeout(3000);

        // Progress: 80%
        await updateProgress(taskId, 80, 'Submitting review');

        // Simulate submission wait
        await page.waitForTimeout(2000);

        // Progress: 90%
        await updateProgress(taskId, 90, 'Verifying submission');

        // Check for success message (adjust selector based on actual Amazon page)
        await page.waitForTimeout(2000);

        // For now, we'll simulate success
        // In production, you would check for actual success indicators
        const success = true; // Replace with actual check

        const screenshotPath = await takeScreenshot(page, taskId, success ? 'success' : 'failed');

        // Progress: 100%
        await updateProgress(taskId, 100, success ? 'Review posted successfully' : 'Review posting failed');

        // Close browser
        await browser.close();

        // Report final status
        await reportCompletion(
            taskId,
            success ? 'completed' : 'failed',
            success ? 'Review posted successfully' : 'Review posting failed',
            screenshotPath
        );

        return success ? 'completed' : 'failed';

    } catch (error) {
        console.error(`[${new Date().toISOString()}] Error:`, error);

        // Try to take error screenshot
        let screenshotPath = null;
        if (browser) {
            try {
                const pages = await browser.pages();
                if (pages.length > 0) {
                    screenshotPath = await takeScreenshot(pages[0], taskId, 'error');
                }
            } catch (screenshotError) {
                console.error('Failed to take error screenshot:', screenshotError.message);
            }
        }

        await updateProgress(taskId, 0, `Error: ${error.message}`);
        await reportCompletion(taskId, 'failed', error.message, screenshotPath);

        if (browser) {
            await browser.close();
        }

        throw error;
    }
}

// Execute
const taskId = process.argv[2];

if (!taskId) {
    console.error('Error: Task ID is required');
    console.error('Usage: node submit-review.js <task_id>');
    process.exit(1);
}

console.log(`[${new Date().toISOString()}] Starting review submission for task ${taskId}...`);

submitReview(taskId)
    .then(status => {
        console.log(`[${new Date().toISOString()}] Task completed with status: ${status}`);
        process.exit(0);
    })
    .catch(error => {
        console.error(`[${new Date().toISOString()}] Task failed:`, error);
        process.exit(1);
    });
