# Complete Setup Guide - Distributed Amazon Review System

This guide walks you through setting up the distributed architecture where:
- **Laravel Web Application** runs on cPanel (cloud) - accessible from anywhere
- **Puppeteer Worker** runs on Static PC (local) - maintains browser cache/sessions

## 📋 Prerequisites

### For cPanel (Cloud)
- cPanel hosting with PHP 8.1+
- MySQL database
- SSH access (recommended)
- Domain name with SSL certificate

### For Static PC (Worker)
- Windows/Linux/Mac PC
- Node.js 16+ installed
- Chrome browser (auto-installed with Puppeteer)
- Stable internet connection
- PC that stays on 24/7

## 🚀 Part 1: cPanel Setup (Laravel Application)

### Step 1: Upload Laravel Project

**Option A: Using Git (Recommended)**

```bash
# SSH into cPanel
ssh username@yourdomain.com

# Navigate to public_html or your desired directory
cd public_html

# Clone repository (or upload files)
git clone your-repository-url .

# Install dependencies
composer install --no-dev --optimize-autoloader
```

**Option B: Using File Manager**

1. Zip your Laravel project locally
2. Upload via cPanel File Manager
3. Extract in `public_html`
4. Use Terminal to run `composer install`

### Step 2: Configure Database

1. **Create MySQL Database** via cPanel

2. **Update `.env` file:**

```env
APP_NAME="Amazon Review Manager"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

# Worker Configuration
WORKER_URL=http://your-static-pc-ip:3000
WORKER_API_TOKEN=your-secure-random-token-here
```

**Generate secure token:**

```bash
# Run this locally or on cPanel
php -r "echo bin2hex(random_bytes(32));"
```

### Step 3: Run Migrations

```bash
php artisan key:generate
php artisan migrate --force
php artisan db:seed
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 4: Configure Web Server

**For Apache (.htaccess already included):**

Make sure `.htaccess` exists in `public/` directory.

**For Nginx, add this to your config:**

```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}

location ~ \.php$ {
    fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
    fastcgi_index index.php;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    include fastcgi_params;
}
```

### Step 5: Set Permissions

```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
```

### Step 6: Test Web Application

Visit: `https://yourdomain.com`

You should see the login page.

## 🖥️ Part 2: Static PC Setup (Worker)

### Step 1: Install Node.js

Download and install from: https://nodejs.org/

Verify installation:

```bash
node --version
npm --version
```

### Step 2: Copy Worker Files

Copy the `worker-server/` directory to your Static PC.

```bash
# On Static PC
cd C:\amazon-review-worker  # Windows
# or
cd ~/amazon-review-worker   # Linux/Mac
```

### Step 3: Install Dependencies

```bash
npm install
```

This will install:
- Express (HTTP server)
- Axios (API communication)
- Puppeteer (browser automation)
- Dotenv (environment configuration)

### Step 4: Configure Worker

Create `.env` file:

```bash
cp .env.example .env
```

Edit `.env`:

```env
# Worker Configuration
WORKER_ID=PC-1
WORKER_PORT=3000

# cPanel API Configuration
CPANEL_API_URL=https://yourdomain.com/api/worker
CPANEL_API_TOKEN=same-token-as-cpanel-env

# Chrome Configuration
CHROME_USER_DATA_DIR=./chrome-data
CHROME_HEADLESS=false

# Heartbeat interval (in milliseconds)
HEARTBEAT_INTERVAL=60000
```

**Important:**
- `CPANEL_API_URL`: Must match your actual domain
- `CPANEL_API_TOKEN`: Must match the token in cPanel's `.env`
- `CHROME_HEADLESS=false`: Set to `true` for production (no visible browser)

### Step 5: Test Worker

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

### Step 6: Configure Port Forwarding (if needed)

If your Static PC is behind a router and cPanel needs to reach it:

1. Find your local IP: `ipconfig` (Windows) or `ifconfig` (Linux/Mac)
2. Configure port forwarding on your router: External Port 3000 → Internal IP:3000
3. Find your public IP: https://whatismyipaddress.com/
4. Update cPanel `.env`: `WORKER_URL=http://your-public-ip:3000`

**Security Note:** For production, use a VPN or reverse tunnel (like ngrok) instead of exposing your PC directly.

### Step 7: Run Worker as Service

**Option A: Using PM2 (Recommended)**

```bash
# Install PM2 globally
npm install -g pm2

# Start worker
pm2 start server.js --name amazon-worker

# Set auto-start on boot
pm2 startup
pm2 save

# View logs
pm2 logs amazon-worker

# Monitor
pm2 monit
```

**Option B: Windows Service**

Use `node-windows` package (see worker-server/README.md).

## 🔗 Part 3: Connecting cPanel and Worker

### Step 1: Update cPanel Environment

In cPanel's `.env`, set:

```env
WORKER_URL=http://your-static-pc-ip:3000
# or if using public IP / domain
WORKER_URL=http://123.456.789.0:3000
```

### Step 2: Test Connection

**From cPanel, test the API:**

```bash
# SSH into cPanel
ssh username@yourdomain.com

# Test worker health
curl http://your-static-pc-ip:3000/health
```

Expected response:

```json
{
  "success": true,
  "status": "free",
  "worker_id": "PC-1",
  "current_task": null
}
```

### Step 3: Verify Heartbeat

Check Laravel logs to see if worker heartbeats are being received:

```bash
tail -f storage/logs/laravel.log
```

You should see entries like:

```
[timestamp] Heartbeat received
```

## 🧪 Part 4: Testing the System

### Test 1: Manual Task Creation

**In cPanel admin panel:**

1. Go to Reviews → Projects → Pending
2. Click on a project
3. Click "Start Review"

**Check Static PC console:**

You should see:

```
[timestamp] Received task request: { task_id: 1, review_id: 123, ... }
[timestamp] Starting task execution in background...
[timestamp] Executing task 1...
[timestamp] Progress: 0% - Starting Chrome browser
[timestamp] Progress: 20% - Chrome opened, navigating to Amazon
...
```

**Check cPanel database:**

```sql
SELECT * FROM task_execution ORDER BY id DESC LIMIT 1;
```

You should see the task with status `processing`.

### Test 2: Progress Tracking

1. Start a task
2. Immediately refresh the page or close browser
3. Reopen the project page

**Expected:** The task should still show as "processing" with current progress.

### Test 3: Worker Busy State

1. Start a task (let it run)
2. Quickly start another task

**Expected:** Second task should fail with "Worker is busy" message.

## 📊 Part 5: Monitoring

### Monitor Worker (Static PC)

```bash
# Using PM2
pm2 status
pm2 logs amazon-worker

# Check health endpoint
curl http://localhost:3000/health
```

### Monitor cPanel (Laravel)

**View recent tasks:**

```sql
SELECT
    te.id,
    te.status,
    te.progress,
    te.message,
    te.worker_id,
    te.created_at,
    te.updated_at
FROM task_execution te
ORDER BY te.id DESC
LIMIT 10;
```

**View worker status:**

```sql
SELECT * FROM worker_status;
```

**Check Laravel logs:**

```bash
tail -f storage/logs/laravel.log
```

## 🔧 Troubleshooting

### Issue: Worker can't connect to cPanel

**Symptoms:** No heartbeats in Laravel logs

**Solutions:**

1. Check `CPANEL_API_URL` in worker's `.env`
2. Check firewall allows outbound connections
3. Verify SSL certificate is valid
4. Test manually:
   ```bash
   curl -X POST https://yourdomain.com/api/worker/heartbeat \
     -H "Content-Type: application/json" \
     -d '{"worker_id":"PC-1","status":"online"}'
   ```

### Issue: cPanel can't reach Worker

**Symptoms:** "Failed to connect to worker" error

**Solutions:**

1. Check `WORKER_URL` in cPanel's `.env`
2. Verify worker is running: `curl http://localhost:3000/health`
3. Check firewall/router allows incoming on port 3000
4. Try using public IP instead of local IP
5. Consider using ngrok for testing:
   ```bash
   ngrok http 3000
   # Use the ngrok URL in WORKER_URL
   ```

### Issue: Tasks stuck in "processing"

**Symptoms:** Task never completes

**Solutions:**

1. Check worker logs: `pm2 logs amazon-worker`
2. Check Puppeteer script errors
3. Manually reset stuck tasks:
   ```sql
   UPDATE task_execution
   SET status = 'failed',
       error_message = 'Timeout - manually reset'
   WHERE status = 'processing'
   AND updated_at < NOW() - INTERVAL 30 MINUTE;
   ```

### Issue: Chrome sessions expire

**Solutions:**

1. Check `CHROME_USER_DATA_DIR` path exists
2. Manually login to Amazon in the Chrome instance
3. Increase Amazon session timeout
4. Verify Chrome closes properly after each task

## 🔐 Security Best Practices

### For cPanel

1. **Use HTTPS:** Always use SSL certificate
2. **Strong Passwords:** Use strong database passwords
3. **API Token:** Use a long, random token
4. **Rate Limiting:** Already configured in routes
5. **Regular Updates:** Keep Laravel and dependencies updated

### For Worker

1. **Firewall:** Only allow connections from cPanel IP
2. **VPN/Tunnel:** Use VPN or reverse tunnel instead of exposing PC directly
3. **API Token:** Keep token secret, don't commit to Git
4. **Regular Updates:** Keep Node.js and Puppeteer updated
5. **Monitoring:** Monitor worker logs for suspicious activity

## 📝 Maintenance

### Daily

- Check worker status
- Monitor task completion rates
- Review error logs

### Weekly

- Check disk space (screenshots, logs)
- Clear old task files
- Review failed tasks

### Monthly

- Update dependencies:
  ```bash
  # Worker
  npm update

  # Laravel
  composer update
  ```
- Clean old database records:
  ```sql
  DELETE FROM task_execution
  WHERE status IN ('completed', 'failed')
  AND created_at < NOW() - INTERVAL 90 DAY;
  ```

## 🎯 Next Steps

1. ✅ Complete cPanel setup
2. ✅ Complete Worker setup
3. ✅ Test connection between systems
4. ✅ Run test tasks
5. ✅ Customize Puppeteer script for your needs
6. ✅ Set up monitoring
7. ✅ Configure backups
8. ✅ Go live!

## 📞 Support

For issues:

1. Check Laravel logs: `storage/logs/laravel.log`
2. Check Worker logs: `pm2 logs amazon-worker`
3. Check database: `task_execution` and `worker_status` tables
4. Review documentation files:
   - `DISTRIBUTED_ARCHITECTURE.md`
   - `IMPROVED_ARCHITECTURE.md`
   - `worker-server/README.md`

## 📚 Additional Resources

- Laravel Documentation: https://laravel.com/docs
- Puppeteer Documentation: https://pptr.dev/
- PM2 Documentation: https://pm2.keymetrics.io/
- Node.js Best Practices: https://github.com/goldbergyoni/nodebestpractices
