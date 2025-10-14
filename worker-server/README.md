# Amazon Review Worker Server

This is the static PC worker that executes Puppeteer automation scripts for the Amazon Review project.

## 🏗️ Architecture

```
┌─────────────────────────────────┐
│    cPanel (Laravel)             │
│    - Web UI                     │
│    - Database                   │
│    - API Endpoints              │
└────────┬────────────────────────┘
         │ HTTP POST
         ▼
┌─────────────────────────────────┐
│    Static PC (Worker)           │
│    - Node.js HTTP Server        │
│    - Puppeteer Scripts          │
│    - Chrome Browser Cache       │
└─────────────────────────────────┘
```

## 📋 Requirements

- Node.js 16+ (Download from https://nodejs.org/)
- Chrome Browser (automatically installed with Puppeteer)
- Windows/Linux/Mac PC that stays on 24/7
- Stable internet connection

## 🚀 Installation

### Step 1: Install Dependencies

```bash
cd worker-server
npm install
```

### Step 2: Configure Environment

Copy `.env.example` to `.env`:

```bash
cp .env.example .env
```

Edit `.env` file:

```env
# Worker Configuration
WORKER_ID=PC-1
WORKER_PORT=3000

# cPanel API Configuration
CPANEL_API_URL=https://yourdomain.com/api/worker
CPANEL_API_TOKEN=your-secure-token-here

# Chrome Configuration
CHROME_USER_DATA_DIR=./chrome-data
CHROME_HEADLESS=false

# Heartbeat interval (in milliseconds)
HEARTBEAT_INTERVAL=60000
```

**Important Settings:**

- `WORKER_ID`: Unique identifier for this worker (e.g., PC-1, PC-2)
- `CPANEL_API_URL`: Your Laravel application API URL
- `CPANEL_API_TOKEN`: Secure token for API authentication (must match Laravel .env)
- `CHROME_USER_DATA_DIR`: Directory to store Chrome cache/sessions
- `CHROME_HEADLESS`: Set to `false` to see browser, `true` for headless mode

### Step 3: Test the Server

```bash
npm start
```

You should see:

```
============================================================
Amazon Review Worker Server
============================================================
Worker ID: PC-1
Server running on: http://localhost:3000
Status: FREE (Ready to receive tasks)
cPanel API: https://yourdomain.com/api/worker
============================================================
```

### Step 4: Test Health Check

Open browser and visit: `http://localhost:3000/health`

You should see:

```json
{
  "success": true,
  "status": "free",
  "worker_id": "PC-1",
  "current_task": null,
  "uptime": 10.5,
  "timestamp": "2025-10-12T05:30:00.000Z"
}
```

## 🔧 Running in Production

### Option 1: Using PM2 (Recommended)

Install PM2 globally:

```bash
npm install -g pm2
```

Start the worker:

```bash
pm2 start server.js --name amazon-worker
```

Useful PM2 commands:

```bash
pm2 status                  # Check status
pm2 logs amazon-worker      # View logs
pm2 restart amazon-worker   # Restart
pm2 stop amazon-worker      # Stop
pm2 delete amazon-worker    # Remove from PM2
```

Set PM2 to auto-start on boot:

```bash
pm2 startup
pm2 save
```

### Option 2: Using Windows Service (Windows Only)

Install `node-windows`:

```bash
npm install -g node-windows
```

Create service script `install-service.js`:

```javascript
const Service = require('node-windows').Service;

const svc = new Service({
  name: 'Amazon Review Worker',
  description: 'Worker for Amazon review automation',
  script: require('path').join(__dirname, 'server.js')
});

svc.on('install', () => {
  svc.start();
});

svc.install();
```

Run:

```bash
node install-service.js
```

## 📡 API Endpoints

The worker exposes the following endpoints:

### POST /execute

Receive task from cPanel and execute it.

**Request:**
```json
{
  "task_id": 123,
  "review_id": 456,
  "account_id": "acc-1",
  "book_asin": "B0FGW5QTD3",
  "review_title": "Great book!",
  "review_description": "I loved this book...",
  "rating": 5
}
```

**Response:**
```json
{
  "success": true,
  "message": "Task started",
  "worker_id": "PC-1",
  "task_id": 123
}
```

### GET /health

Health check endpoint.

**Response:**
```json
{
  "success": true,
  "status": "free|busy",
  "worker_id": "PC-1",
  "current_task": null,
  "uptime": 3600.5,
  "timestamp": "2025-10-12T05:30:00.000Z"
}
```

### GET /status

Get worker status.

**Response:**
```json
{
  "success": true,
  "worker_id": "PC-1",
  "status": "online|busy",
  "current_task_id": null,
  "uptime": 3600.5
}
```

## 🔍 Customizing Review Submission

The actual review submission logic is in `scripts/submit-review.js`.

Look for this section:

```javascript
// ==========================================
// YOUR EXISTING REVIEW SUBMISSION LOGIC HERE
// ==========================================
```

Replace it with your actual Amazon review submission code.

**Example:**

```javascript
// Navigate to review form
const writeReviewBtn = await page.$('a[data-hook="write-review-button"]');
if (writeReviewBtn) {
    await writeReviewBtn.click();
    await page.waitForTimeout(2000);
}

// Select rating
const ratingStars = await page.$(`a[data-value="${taskData.rating}"]`);
if (ratingStars) {
    await ratingStars.click();
}

// Fill title
await page.type('#review-title-input', taskData.review_title);

// Fill description
await page.type('#review-body-textarea', taskData.review_description);

// Submit
await page.click('button[type="submit"]');
await page.waitForTimeout(3000);
```

## 📁 Directory Structure

```
worker-server/
├── server.js              # Main HTTP server
├── package.json           # Node.js dependencies
├── .env                   # Configuration (create from .env.example)
├── .env.example           # Example configuration
├── README.md              # This file
├── scripts/
│   └── submit-review.js   # Puppeteer review submission script
├── tasks/                 # Temporary task data files
├── chrome-data/           # Chrome browser cache/sessions
├── screenshots/           # Screenshots for debugging
└── logs/                  # Log files
```

## 🛠️ Troubleshooting

### Worker won't start

1. Check Node.js is installed: `node --version`
2. Check all dependencies are installed: `npm install`
3. Check port 3000 is not in use: `netstat -an | findstr 3000`

### Can't connect to cPanel

1. Check `CPANEL_API_URL` is correct in `.env`
2. Check `CPANEL_API_TOKEN` matches Laravel .env
3. Check firewall allows outbound connections
4. Test API manually: `curl https://yourdomain.com/api/worker/heartbeat`

### Tasks not executing

1. Check Puppeteer script path is correct
2. Check Chrome can launch: `node -e "require('puppeteer').launch().then(b => b.close())"`
3. Check task JSON files are being created in `tasks/` folder
4. Check logs: `pm2 logs amazon-worker`

### Chrome sessions expire

1. Increase session timeout in Amazon
2. Re-login to Amazon accounts manually in Chrome
3. Check `CHROME_USER_DATA_DIR` path is correct
4. Make sure Chrome closes properly after each task

## 📊 Monitoring

### View Logs

```bash
# With PM2
pm2 logs amazon-worker

# Or check log files
tail -f logs/worker.log
```

### Check Status

Visit: `http://localhost:3000/status`

Or use cPanel admin dashboard to see all workers.

## 🔐 Security

1. **API Token**: Use a strong, random token for `CPANEL_API_TOKEN`
2. **Firewall**: Only allow incoming connections from cPanel IP
3. **HTTPS**: Use HTTPS for cPanel API (not HTTP)
4. **Updates**: Keep Node.js and Puppeteer updated

## 📞 Support

For issues, check:
1. Worker logs: `pm2 logs amazon-worker`
2. Laravel logs: `storage/logs/laravel.log` on cPanel
3. Chrome DevTools in visible mode (`CHROME_HEADLESS=false`)
4. Screenshots in `screenshots/` folder

## 🎯 Next Steps

1. ✅ Install and configure worker
2. ✅ Test with cPanel
3. ✅ Customize review submission logic
4. ✅ Set up PM2 for production
5. ✅ Configure auto-start on boot
6. ✅ Monitor and maintain
