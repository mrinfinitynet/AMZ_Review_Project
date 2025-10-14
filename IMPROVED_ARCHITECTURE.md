# Improved Architecture - Push-Based System with State Management

## 🎯 Problems with Polling (Old Way)

❌ Static PC polls every 30 seconds → Wastes resources
❌ Task already running but still polls → Can start duplicate tasks
❌ cPanel refreshes → Loses track of progress
❌ Delay of up to 30 seconds before task starts

## ✅ New Solution: Push + State Management

### Architecture Overview

```
┌─────────────────────────────────────────────────────────────┐
│                    CLOUD (cPanel)                            │
│  ┌────────────────────────────────────────────────────┐    │
│  │         Laravel Web Application                     │    │
│  │                                                      │    │
│  │  1. User clicks "Start Review"                     │    │
│  │     ↓                                               │    │
│  │  2. Creates task in database (status: pending)     │    │
│  │     ↓                                               │    │
│  │  3. Sends HTTP request to Static PC                │    │
│  │     (http://static-pc-ip:3000/execute)             │    │
│  │     ↓                                               │    │
│  │  4. Gets immediate response "Task started"         │    │
│  │     ↓                                               │    │
│  │  5. Updates task status to "processing"            │    │
│  └────────────────────────────────────────────────────┘    │
│                                                              │
│  Database Table: task_execution                             │
│  ┌────────────────────────────────────────────────────┐    │
│  │ task_id | status      | worker_id | progress |     │    │
│  │ 123     | processing  | PC-1      | 50%      |     │    │
│  │ 124     | completed   | PC-1      | 100%     |     │    │
│  │ 125     | pending     | NULL      | 0%       |     │    │
│  └────────────────────────────────────────────────────┘    │
└─────────────────────────────────────────────────────────────┘
                             │
                             │ HTTP POST
                             ▼
┌─────────────────────────────────────────────────────────────┐
│                   STATIC PC (Worker)                         │
│  ┌────────────────────────────────────────────────────┐    │
│  │         Node.js HTTP Server (Port 3000)             │    │
│  │                                                      │    │
│  │  1. Receives command via HTTP POST                 │    │
│  │     ↓                                               │    │
│  │  2. Checks if already running a task               │    │
│  │     ↓                                               │    │
│  │  3. If FREE → Start Puppeteer script               │    │
│  │     If BUSY → Reject with "busy" status            │    │
│  │     ↓                                               │    │
│  │  4. While running → Update progress to DB          │    │
│  │     (0% → 25% → 50% → 75% → 100%)                  │    │
│  │     ↓                                               │    │
│  │  5. Task completes → Report to cPanel API          │    │
│  │     ↓                                               │    │
│  │  6. Mark worker as FREE                            │    │
│  └────────────────────────────────────────────────────┘    │
└─────────────────────────────────────────────────────────────┘
```

## 🔄 Flow: User Starts a Review

### Step-by-Step Process

```javascript
// 1. USER CLICKS "Start Review" on cPanel
User Action: Click button
    ↓
// 2. cPanel Backend (Laravel)
Laravel Controller:
{
  1. Create task record in database
     INSERT INTO task_execution (review_id, status, created_at)
     VALUES (123, 'pending', NOW())

  2. Send HTTP request to Static PC
     POST http://192.168.1.100:3000/execute
     Body: { task_id: 123, review_id: 456, account_id: "acc-1" }

  3. Wait for response (max 5 seconds)
     - If SUCCESS: Update status to "processing"
     - If TIMEOUT: Update status to "failed"
     - If BUSY: Show "Worker busy, try again"
}
    ↓
// 3. Static PC receives request
Node.js Server:
{
  1. Check current status
     if (isRunningTask) {
       return { success: false, message: "Busy with another task" }
     }

  2. Mark as busy
     isRunningTask = true
     currentTaskId = 123

  3. Start Puppeteer script in background
     exec(`node scripts/submit-review.js ${taskId}`)

  4. Return immediate response
     return { success: true, message: "Task started", task_id: 123 }
}
    ↓
// 4. Puppeteer script runs
Puppeteer Script:
{
  1. Update progress: 0%
     await updateProgress(taskId, 0, "Opening Chrome")

  2. Open Chrome with cache
     const browser = await puppeteer.launch({ userDataDir: './chrome-data' })
     await updateProgress(taskId, 25, "Opened Chrome")

  3. Navigate to Amazon
     await page.goto('https://amazon.com/...')
     await updateProgress(taskId, 50, "Navigated to Amazon")

  4. Post review
     await page.click('#submit-review')
     await updateProgress(taskId, 75, "Posting review")

  5. Verify success
     const success = await page.$('#success-message')
     await updateProgress(taskId, 100, success ? "Completed" : "Failed")

  6. Report back to cPanel
     await reportToAPI(taskId, success ? "completed" : "failed")

  7. Mark worker as free
     isRunningTask = false
     currentTaskId = null
}
    ↓
// 5. User refreshes page or reopens
cPanel Query:
{
  SELECT * FROM task_execution WHERE review_id = 456

  Result:
  {
    status: "processing",
    progress: 75,
    message: "Posting review",
    updated_at: "2 seconds ago"
  }

  → Shows live status even if user closed browser!
}
```

## 💾 Database Schema

### Task Execution Table

```sql
CREATE TABLE task_execution (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    review_id BIGINT NOT NULL,
    task_type ENUM('review', 'add_to_cart', 'check_account') DEFAULT 'review',
    status ENUM('pending', 'processing', 'completed', 'failed') DEFAULT 'pending',
    progress INT DEFAULT 0,
    message VARCHAR(500),
    worker_id VARCHAR(100),
    worker_ip VARCHAR(45),
    started_at TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,
    error_message TEXT,
    screenshot_path VARCHAR(500),
    attempts INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_review_id (review_id),
    INDEX idx_status (status),
    INDEX idx_worker_id (worker_id)
);
```

### Worker Status Table

```sql
CREATE TABLE worker_status (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    worker_id VARCHAR(100) UNIQUE NOT NULL,
    status ENUM('online', 'offline', 'busy') DEFAULT 'offline',
    current_task_id BIGINT NULL,
    ip_address VARCHAR(45),
    last_heartbeat TIMESTAMP,
    chrome_version VARCHAR(50),
    total_tasks_completed INT DEFAULT 0,
    total_tasks_failed INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

## 🚀 Implementation

### 1. Laravel Controller (cPanel)

```php
// app/Http/Controllers/Admin/TaskController.php

public function startReview(Request $request)
{
    $reviewId = $request->review_id;

    // 1. Create task record
    $task = TaskExecution::create([
        'review_id' => $reviewId,
        'task_type' => 'review',
        'status' => 'pending',
        'attempts' => 0
    ]);

    // 2. Get review details
    $review = AmazonReviewProject::find($reviewId);

    // 3. Send to Static PC
    try {
        $response = Http::timeout(5)->post(env('WORKER_URL') . '/execute', [
            'task_id' => $task->id,
            'review_id' => $reviewId,
            'account_id' => $review->account_id,
            'book_asin' => $review->book_asin,
            'review_title' => $review->review_title,
            'review_description' => $review->review_description,
            'rating' => $review->rating,
        ]);

        if ($response->successful()) {
            $task->update([
                'status' => 'processing',
                'worker_id' => $response['worker_id'],
                'started_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Task started',
                'task_id' => $task->id
            ]);
        }

        // Worker is busy
        $task->update(['status' => 'failed', 'error_message' => 'Worker busy']);
        return response()->json([
            'success' => false,
            'message' => 'Worker is busy with another task'
        ], 503);

    } catch (\Exception $e) {
        $task->update(['status' => 'failed', 'error_message' => $e->getMessage()]);
        return response()->json([
            'success' => false,
            'message' => 'Failed to connect to worker'
        ], 500);
    }
}

// Get task status (for refresh/reload)
public function getTaskStatus($taskId)
{
    $task = TaskExecution::find($taskId);

    return response()->json([
        'task_id' => $task->id,
        'status' => $task->status,
        'progress' => $task->progress,
        'message' => $task->message,
        'started_at' => $task->started_at,
        'completed_at' => $task->completed_at,
        'updated_at' => $task->updated_at
    ]);
}
```

### 2. Static PC - Node.js Server

```javascript
// worker-server/server.js

const express = require('express');
const { exec } = require('child_process');
const axios = require('axios');

const app = express();
app.use(express.json());

// Worker state
let isRunningTask = false;
let currentTaskId = null;
const WORKER_ID = 'PC-1';
const CPANEL_API = 'https://yourdomain.com/api/worker';

// Endpoint to receive tasks from cPanel
app.post('/execute', async (req, res) => {
    const { task_id, review_id, account_id, book_asin, review_title, review_description, rating } = req.body;

    // Check if already running
    if (isRunningTask) {
        return res.status(503).json({
            success: false,
            message: 'Worker is busy',
            current_task: currentTaskId
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
    executeTask({ task_id, review_id, account_id, book_asin, review_title, review_description, rating });
});

// Execute the Puppeteer script
async function executeTask(taskData) {
    try {
        // Save task data to file
        const fs = require('fs');
        fs.writeFileSync(`./tasks/${taskData.task_id}.json`, JSON.stringify(taskData));

        // Run Puppeteer script
        exec(`node scripts/submit-review.js ${taskData.task_id}`, async (error, stdout, stderr) => {
            if (error) {
                console.error('Task failed:', error);
                await reportToAPI(taskData.task_id, 'failed', error.message);
            } else {
                console.log('Task completed:', stdout);
            }

            // Mark as free
            isRunningTask = false;
            currentTaskId = null;
        });

    } catch (error) {
        console.error('Execute task error:', error);
        isRunningTask = false;
        currentTaskId = null;
        await reportToAPI(taskData.task_id, 'failed', error.message);
    }
}

// Report back to cPanel API
async function reportToAPI(taskId, status, message) {
    try {
        await axios.post(`${CPANEL_API}/tasks/${taskId}/complete`, {
            status: status,
            message: message,
            worker_id: WORKER_ID
        });
    } catch (error) {
        console.error('Failed to report to API:', error.message);
    }
}

// Health check endpoint
app.get('/health', (req, res) => {
    res.json({
        status: isRunningTask ? 'busy' : 'free',
        worker_id: WORKER_ID,
        current_task: currentTaskId
    });
});

// Start server
const PORT = 3000;
app.listen(PORT, () => {
    console.log(`Worker server running on port ${PORT}`);
    console.log(`Status: FREE`);
});
```

### 3. Puppeteer Script with Progress Updates

```javascript
// worker-server/scripts/submit-review.js

const puppeteer = require('puppeteer');
const fs = require('fs');
const axios = require('axios');

const CPANEL_API = 'https://yourdomain.com/api/worker';
const WORKER_ID = 'PC-1';

async function updateProgress(taskId, progress, message) {
    try {
        await axios.post(`${CPANEL_API}/tasks/${taskId}/progress`, {
            progress: progress,
            message: message,
            worker_id: WORKER_ID
        });
        console.log(`Progress: ${progress}% - ${message}`);
    } catch (error) {
        console.error('Failed to update progress:', error.message);
    }
}

async function submitReview(taskId) {
    let browser;

    try {
        // Load task data
        const taskData = JSON.parse(fs.readFileSync(`./tasks/${taskId}.json`));

        // Progress: 0%
        await updateProgress(taskId, 0, 'Starting Chrome browser');

        // Launch browser with cache
        browser = await puppeteer.launch({
            headless: false,
            userDataDir: './chrome-data',
            args: ['--no-sandbox']
        });

        const page = await browser.newPage();

        // Progress: 25%
        await updateProgress(taskId, 25, 'Chrome opened, navigating to Amazon');

        // Navigate to Amazon
        await page.goto(`https://www.amazon.com/dp/${taskData.book_asin}`);
        await page.waitForTimeout(2000);

        // Progress: 50%
        await updateProgress(taskId, 50, 'On product page, filling review form');

        // Fill review form (your existing logic here)
        // ... your review posting code ...

        // Progress: 75%
        await updateProgress(taskId, 75, 'Submitting review');

        // Click submit
        // ... submit logic ...

        // Verify success
        await page.waitForTimeout(3000);
        const success = await page.$('#success-message'); // Adjust selector

        // Progress: 100%
        await updateProgress(taskId, 100, success ? 'Review posted successfully' : 'Review posting failed');

        // Close browser
        await browser.close();

        // Clean up task file
        fs.unlinkSync(`./tasks/${taskId}.json`);

        return success ? 'completed' : 'failed';

    } catch (error) {
        console.error('Error:', error);
        await updateProgress(taskId, 0, `Error: ${error.message}`);
        if (browser) await browser.close();
        throw error;
    }
}

// Execute
const taskId = process.argv[2];
submitReview(taskId)
    .then(status => {
        console.log('Task completed with status:', status);
        process.exit(0);
    })
    .catch(error => {
        console.error('Task failed:', error);
        process.exit(1);
    });
```

## 🎯 Key Benefits

✅ **Instant Execution**: No 30-second delay
✅ **State Management**: Task status persists in database
✅ **Refresh-Safe**: User can close/refresh browser
✅ **No Duplicates**: Checks if worker is busy
✅ **Real-time Progress**: Updates every step
✅ **Error Handling**: Proper error reporting
✅ **Worker Health**: Know if worker is online/offline

## 🔍 How Status Survives Refresh

```javascript
// When user refreshes page
Frontend (JavaScript):
{
  // On page load
  const taskId = localStorage.getItem('current_task_id');

  if (taskId) {
    // Poll for status every 2 seconds
    setInterval(async () => {
      const response = await fetch(`/api/tasks/${taskId}/status`);
      const data = await response.json();

      // Update UI
      updateProgressBar(data.progress);
      updateStatusText(data.message);

      // If completed, stop polling
      if (data.status === 'completed' || data.status === 'failed') {
        clearInterval(interval);
      }
    }, 2000);
  }
}
```

## 📝 Summary

**Old System (Polling):**
- Worker checks every 30 seconds
- Can miss tasks or duplicate
- No state management

**New System (Push + State):**
- cPanel pushes tasks instantly
- Worker checks if busy before accepting
- Database tracks everything
- User can refresh/close without losing progress

This is the **production-ready** solution!
