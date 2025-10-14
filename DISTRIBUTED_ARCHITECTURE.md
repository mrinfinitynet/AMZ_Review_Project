# Distributed Architecture - Multi-Device Setup

## 📋 Overview

This document explains how to run the Laravel web application on **cPanel (cloud)** while keeping the Puppeteer automation scripts on a **static PC (local worker)**.

## 🏗️ Architecture Diagram

```
┌─────────────────────────────────────────────────────────────┐
│                        CLOUD (cPanel)                        │
│  ┌────────────────────────────────────────────────────┐    │
│  │         Laravel Web Application                     │    │
│  │  - Web UI (accessible from anywhere)               │    │
│  │  - MySQL Database (projects, accounts, reviews)    │    │
│  │  - API Endpoints (for worker communication)        │    │
│  │  - Job Queue (Redis/Database)                      │    │
│  └────────────────────────────────────────────────────┘    │
│                            ▲                                 │
│                            │ HTTPS API                       │
│                            │                                 │
└────────────────────────────┼─────────────────────────────────┘
                             │
                             │
                ┌────────────┴────────────┐
                │    Internet/Network     │
                └────────────┬────────────┘
                             │
                             ▼
┌─────────────────────────────────────────────────────────────┐
│                   LOCAL WORKER (Static PC)                   │
│  ┌────────────────────────────────────────────────────┐    │
│  │         Puppeteer Worker Script                     │    │
│  │  - Chrome Browser (with extensions)                │    │
│  │  - Browser Cache & Cookies                         │    │
│  │  - Amazon Account Sessions                         │    │
│  │  - Automated Review Posting                        │    │
│  │  - Polls API for new tasks                         │    │
│  └────────────────────────────────────────────────────┘    │
└─────────────────────────────────────────────────────────────┘
```

## 🎯 How It Works

### 1. **User Interaction (Any Device)**
```
User (Mobile/Laptop/PC)
    ↓
Access Web Application (https://yourdomain.com)
    ↓
Create/Manage Projects, Accounts, Reviews
    ↓
Data saved to MySQL Database (on cPanel)
```

### 2. **Worker Processing (Static PC)**
```
Worker Script (Running 24/7 on Static PC)
    ↓
Polls API every 30 seconds: "Any new tasks?"
    ↓
If YES: Download task details
    ↓
Open Chrome with cached sessions
    ↓
Automate Amazon review posting
    ↓
Report results back to API
    ↓
Update database status
```

## 🔧 Components

### A. **Web Application (cPanel)**

**Location:** `https://yourdomain.com`

**Responsibilities:**
- ✅ User interface (accessible from anywhere)
- ✅ Database management
- ✅ API endpoints for worker communication
- ✅ Task queue management
- ✅ Results display

**Tech Stack:**
- Laravel 10+
- MySQL Database
- Redis (optional, for queue)
- PHP 8.1+

### B. **Worker Script (Static PC)**

**Location:** Your home/office PC that stays on 24/7

**Responsibilities:**
- ✅ Run Puppeteer/Chrome automation
- ✅ Maintain browser cache/sessions
- ✅ Keep Amazon accounts logged in
- ✅ Execute review posting tasks
- ✅ Report back to web application

**Tech Stack:**
- Node.js
- Puppeteer
- Chrome Browser
- PM2 (for keeping script running)

## 📡 API Communication

### API Endpoints (Web Application)

#### 1. Get Pending Tasks
```http
GET /api/worker/tasks
Authorization: Bearer {WORKER_TOKEN}

Response:
{
  "tasks": [
    {
      "id": 1,
      "type": "review",
      "review_id": 123,
      "account_id": "account-1",
      "book_asin": "B0FGW5QTD3",
      "review_title": "Great book!",
      "review_description": "I loved this book...",
      "rating": 5
    }
  ]
}
```

#### 2. Update Task Status
```http
POST /api/worker/tasks/{task_id}/status
Authorization: Bearer {WORKER_TOKEN}

Body:
{
  "status": "completed|failed",
  "message": "Review posted successfully",
  "screenshot": "base64_image_data" (optional)
}

Response:
{
  "success": true,
  "message": "Status updated"
}
```

#### 3. Heartbeat (Worker Health Check)
```http
POST /api/worker/heartbeat
Authorization: Bearer {WORKER_TOKEN}

Body:
{
  "worker_id": "worker-pc-1",
  "status": "online",
  "chrome_version": "120.0.0.0",
  "last_task_at": "2025-10-12 10:30:00"
}
```

## 🚀 Setup Instructions

### Part 1: Web Application Setup (cPanel)

#### Step 1: Upload Laravel to cPanel
```bash
# 1. Create a ZIP of your Laravel project
zip -r project.zip .

# 2. Upload to cPanel via File Manager or FTP

# 3. Extract files
unzip project.zip

# 4. Move files to public_html
mv * public_html/
```

#### Step 2: Configure Environment
```env
# .env file on cPanel
APP_URL=https://yourdomain.com
DB_HOST=localhost
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Worker API Token (generate secure token)
WORKER_API_TOKEN=your-secure-token-here
```

#### Step 3: Run Migrations
```bash
php artisan migrate
php artisan db:seed
```

### Part 2: Worker Script Setup (Static PC)

#### Step 1: Install Requirements
```bash
# Install Node.js (if not installed)
# Download from: https://nodejs.org/

# Verify installation
node --version
npm --version
```

#### Step 2: Create Worker Directory
```bash
mkdir amazon-review-worker
cd amazon-review-worker
npm init -y
```

#### Step 3: Install Dependencies
```bash
npm install puppeteer axios dotenv pm2 -g
```

#### Step 4: Create Worker Configuration
```bash
# .env file for worker
API_URL=https://yourdomain.com/api
WORKER_TOKEN=your-secure-token-here
WORKER_ID=worker-pc-1
POLL_INTERVAL=30000
CHROME_USER_DATA=./chrome-data
```

## 💾 Database Schema

### Workers Table (Add to Laravel)
```sql
CREATE TABLE workers (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    worker_id VARCHAR(255) UNIQUE,
    name VARCHAR(255),
    status ENUM('online', 'offline', 'busy'),
    last_heartbeat TIMESTAMP,
    chrome_version VARCHAR(50),
    ip_address VARCHAR(45),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Tasks Queue Table
```sql
CREATE TABLE task_queue (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    review_id BIGINT,
    worker_id VARCHAR(255),
    status ENUM('pending', 'processing', 'completed', 'failed'),
    priority INT DEFAULT 0,
    attempts INT DEFAULT 0,
    result_message TEXT,
    started_at TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

## 🔐 Security Considerations

### 1. **API Authentication**
```php
// Use Bearer Token authentication
// Generate secure token: php artisan key:generate
```

### 2. **Rate Limiting**
```php
// Limit API requests from worker
Route::middleware('throttle:60,1')->group(function () {
    // Worker routes
});
```

### 3. **IP Whitelisting** (Optional)
```php
// Only allow requests from worker IP
if (!in_array($request->ip(), ['YOUR_WORKER_IP'])) {
    abort(403);
}
```

## 📊 Monitoring & Logs

### Web Application Logs
```bash
# Laravel logs
tail -f storage/logs/laravel.log

# Worker activity log
tail -f storage/logs/worker.log
```

### Worker Script Logs
```bash
# PM2 logs
pm2 logs amazon-worker

# Custom log file
tail -f worker-script.log
```

## 🛠️ Troubleshooting

### Problem: Worker can't connect to API
**Solution:**
1. Check firewall settings
2. Verify API URL is accessible
3. Check worker token is correct
4. Ensure SSL certificate is valid

### Problem: Chrome sessions expire
**Solution:**
1. Increase session timeout
2. Re-login to Amazon accounts
3. Check cookie persistence
4. Verify user data directory

### Problem: Tasks stuck in processing
**Solution:**
1. Check worker heartbeat
2. Restart worker script
3. Clear stuck tasks: `UPDATE task_queue SET status='pending' WHERE status='processing' AND updated_at < NOW() - INTERVAL 10 MINUTE`

## 🎯 Benefits of This Architecture

✅ **Access Anywhere**: Manage projects from any device
✅ **Centralized Database**: Single source of truth
✅ **Scalable**: Add more workers as needed
✅ **Reliable**: Worker maintains sessions/cache
✅ **Secure**: API authentication
✅ **Maintainable**: Separate concerns
✅ **Flexible**: Easy to add features

## 📱 Device Access Matrix

| Device Type | Can Access Web UI | Can Run Worker |
|-------------|-------------------|----------------|
| Mobile      | ✅ Yes            | ❌ No          |
| Laptop      | ✅ Yes            | ⚠️ Not recommended |
| Desktop PC  | ✅ Yes            | ✅ Yes (Best)  |
| Tablet      | ✅ Yes            | ❌ No          |
| Server      | ✅ Yes            | ✅ Yes         |

## 🔄 Workflow Example

### Scenario: Add a new review project

1. **User** (on mobile) logs into https://yourdomain.com
2. **User** clicks "Add Project" and fills in details
3. **System** saves to MySQL database
4. **System** creates tasks in task_queue
5. **Worker** (on static PC) polls API every 30 seconds
6. **Worker** sees new task
7. **Worker** opens Chrome with cached Amazon session
8. **Worker** posts the review
9. **Worker** reports success back to API
10. **System** updates database status
11. **User** (on mobile) sees "Approved" status

## 📚 Next Steps

1. ✅ Read this documentation
2. ✅ Set up web application on cPanel
3. ✅ Configure worker script on static PC
4. ✅ Test API communication
5. ✅ Run first automated task
6. ✅ Monitor and optimize

## 💡 Tips

- Keep your static PC running 24/7
- Use UPS for power backup
- Set up PM2 to auto-restart worker on failure
- Monitor worker health via heartbeat
- Keep Chrome updated
- Backup chrome-data folder regularly
- Use VPN if needed for Amazon access

---

**Need Help?** Check the implementation files:
- `routes/api.php` - API routes
- `app/Http/Controllers/API/WorkerController.php` - Worker API controller
- `worker-script/index.js` - Worker script
