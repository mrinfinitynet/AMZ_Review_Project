# Universal Service API - Setup Guide

## Overview

The Universal Service API now works as a **proxy/gateway** that forwards requests to an external Claude-based API server. This allows you to centralize your data management and access it from multiple applications.

---

## Architecture

```
Your Local App → UniversalServiceController → External Claude API → Database
     ↓                                                 ↓
Returns response  ←────────────────────────────────  Returns data
```

---

## Setup Steps

### Step 1: Configure Environment Variables

Open your `.env` file and configure the Claude API settings:

```env
# Claude Server API Configuration
CLAUDE_API_BASE_URL=http://127.0.0.1:8000/api
CLAUDE_API_TOKEN=your-secure-api-token-here
```

**For Production:**
```env
CLAUDE_API_BASE_URL=https://your-claude-server.com/api
CLAUDE_API_TOKEN=your-production-api-token-here
```

### Step 2: Set Your External API URL

Replace `CLAUDE_API_BASE_URL` with your actual Claude server URL.

**Examples:**
- Local development: `http://127.0.0.1:8000/api`
- Production server: `https://api.yourcompany.com/api`
- Remote server: `https://claude-server.example.com/api`

### Step 3: Set Authentication Token (Optional)

If your Claude API requires authentication, set the `CLAUDE_API_TOKEN`:

```env
CLAUDE_API_TOKEN=eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...
```

If no authentication is needed, leave it empty:
```env
CLAUDE_API_TOKEN=
```

### Step 4: Test the Connection

Test if your local app can connect to the Claude API:

```bash
GET http://your-local-app.com/api/service/test-connection
```

**Success Response:**
```json
{
  "success": true,
  "message": "Connection successful",
  "data": {
    "base_url": "http://127.0.0.1:8000/api",
    "status_code": 200,
    "response_time": 0.234
  }
}
```

**Error Response:**
```json
{
  "success": false,
  "message": "Connection failed",
  "error": "cURL error 7: Failed to connect..."
}
```

### Step 5: Check Configuration

View your current API configuration:

```bash
GET http://your-local-app.com/api/service/config
```

**Response:**
```json
{
  "success": true,
  "data": {
    "base_url": "http://127.0.0.1:8000/api",
    "has_token": true,
    "allowed_tables": [
      "amazon_review_accounts",
      "amazon_review_projects",
      "amazon_review_project_histories",
      "clients"
    ],
    "timeout": 30,
    "status": "ready"
  }
}
```

---

## How It Works

### Request Flow

1. **Your app makes a request:**
   ```bash
   GET http://your-app.com/api/service/clients
   ```

2. **UniversalServiceController forwards to Claude API:**
   ```bash
   GET http://claude-server.com/api/clients
   Headers: {
     "Authorization": "Bearer your-token",
     "Accept": "application/json",
     "Content-Type": "application/json"
   }
   ```

3. **Claude API processes and returns data:**
   ```json
   {
     "success": true,
     "data": [...]
   }
   ```

4. **Your app receives the response**

---

## API Endpoints

All endpoints forward to your Claude server:

### GET All Records
```bash
# Local call
GET /api/service/clients?type=Client1

# Forwards to
GET {CLAUDE_API_BASE_URL}/clients?type=Client1
```

### GET Single Record
```bash
# Local call
GET /api/service/clients/5

# Forwards to
GET {CLAUDE_API_BASE_URL}/clients/5
```

### POST Create Record
```bash
# Local call
POST /api/service/clients
Body: {"name": "New Client", "code": "Client3"}

# Forwards to
POST {CLAUDE_API_BASE_URL}/clients
Body: {"name": "New Client", "code": "Client3"}
```

### PUT Update Record
```bash
# Local call
PUT /api/service/clients/5
Body: {"name": "Updated Name"}

# Forwards to
PUT {CLAUDE_API_BASE_URL}/clients/5
Body: {"name": "Updated Name"}
```

### DELETE Record
```bash
# Local call
DELETE /api/service/clients/5

# Forwards to
DELETE {CLAUDE_API_BASE_URL}/clients/5
```

### Bulk Operations
```bash
# Local call
POST /api/service/clients/bulk-delete
Body: {"ids": [1, 2, 3]}

# Forwards to
POST {CLAUDE_API_BASE_URL}/clients/bulk-delete
Body: {"ids": [1, 2, 3]}
```

---

## Authentication

The controller automatically adds authentication headers to all requests:

```php
Headers: {
  "Authorization": "Bearer {CLAUDE_API_TOKEN}",
  "Accept": "application/json",
  "Content-Type": "application/json"
}
```

If `CLAUDE_API_TOKEN` is empty, the Authorization header is not sent.

---

## Timeout Configuration

All requests have a **30-second timeout** by default. Connection test has a **10-second timeout**.

To change the timeout, modify the controller:

```php
$response = Http::withHeaders($this->getHeaders())
    ->timeout(60) // Change to 60 seconds
    ->get("{$this->baseUrl}/{$table}", $params);
```

---

## Error Handling

### Connection Errors

If the Claude API is unreachable:

```json
{
  "success": false,
  "message": "Failed to retrieve records: cURL error 7: Failed to connect to..."
}
```

### API Errors

If the Claude API returns an error:

```json
{
  "success": false,
  "message": "API request failed",
  "error": "Record not found",
  "errors": {
    "id": ["The selected id is invalid."]
  }
}
```

### Validation Errors

If request validation fails:

```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "ids": ["The ids field is required."]
  }
}
```

---

## Deployment Scenarios

### Scenario 1: Multiple Apps → Single Claude Server

**Setup:**
- App 1 (Admin Panel) → Claude API Server
- App 2 (Mobile App) → Claude API Server
- App 3 (Dashboard) → Claude API Server

**Configuration:**

All apps use the same `.env` settings:
```env
CLAUDE_API_BASE_URL=https://central-api.yourcompany.com/api
CLAUDE_API_TOKEN=shared-api-token
```

### Scenario 2: Development → Production

**Development (.env.local):**
```env
CLAUDE_API_BASE_URL=http://localhost:8000/api
CLAUDE_API_TOKEN=dev-token
```

**Production (.env.production):**
```env
CLAUDE_API_BASE_URL=https://api.yourcompany.com/api
CLAUDE_API_TOKEN=prod-secure-token-xyz
```

### Scenario 3: Microservices Architecture

**Service A (Reviews):**
```env
CLAUDE_API_BASE_URL=https://reviews-api.internal/api
```

**Service B (Clients):**
```env
CLAUDE_API_BASE_URL=https://clients-api.internal/api
```

**Service C (Analytics):**
```env
CLAUDE_API_BASE_URL=https://analytics-api.internal/api
```

---

## Security Best Practices

### 1. Secure Your API Token

Generate a strong random token:

```bash
php artisan tinker
>>> Str::random(64)
```

Use environment-specific tokens:
```env
# Development
CLAUDE_API_TOKEN=dev-token-12345

# Production
CLAUDE_API_TOKEN=prod-secure-xyz-789-abc-def
```

### 2. Use HTTPS in Production

Always use HTTPS for production:
```env
CLAUDE_API_BASE_URL=https://api.yourcompany.com/api
```

Never use HTTP in production:
```env
# DON'T DO THIS IN PRODUCTION
CLAUDE_API_BASE_URL=http://api.yourcompany.com/api
```

### 3. Restrict Allowed Tables

Modify the `$allowedTables` array to limit which tables can be accessed:

```php
protected $allowedTables = [
    'amazon_review_accounts',
    'clients', // Only allow these two tables
];
```

### 4. Rate Limiting

Add rate limiting to protect your API:

```php
// In routes/api.php
Route::middleware('throttle:60,1')->group(function () {
    // Service routes here
});
```

---

## Troubleshooting

### Problem: Connection Timeout

**Error:**
```
Failed to retrieve records: cURL error 28: Operation timed out
```

**Solutions:**
1. Check if Claude API server is running
2. Verify the `CLAUDE_API_BASE_URL` is correct
3. Increase timeout value
4. Check firewall/network settings

### Problem: 401 Unauthorized

**Error:**
```json
{
  "success": false,
  "message": "API request failed",
  "error": "Unauthorized"
}
```

**Solutions:**
1. Verify `CLAUDE_API_TOKEN` is correct
2. Check if token has expired
3. Ensure Claude API is accepting the token format

### Problem: 404 Not Found

**Error:**
```json
{
  "success": false,
  "message": "API request failed",
  "error": "Not found"
}
```

**Solutions:**
1. Verify the `CLAUDE_API_BASE_URL` path is correct
2. Check if the endpoint exists on Claude API
3. Ensure table name is in `$allowedTables`

### Problem: SSL Certificate Error

**Error:**
```
cURL error 60: SSL certificate problem
```

**Solutions:**
1. Update SSL certificates
2. For development only, disable SSL verification (not recommended for production)

---

## Testing Examples

### Test with cURL

```bash
# Test connection
curl http://your-app.com/api/service/test-connection

# Get config
curl http://your-app.com/api/service/config

# Get all clients
curl http://your-app.com/api/service/clients

# Create new record
curl -X POST http://your-app.com/api/service/clients \
  -H "Content-Type: application/json" \
  -d '{"name": "Test Client", "code": "TEST"}'
```

### Test with Postman

1. **Test Connection**
   - Method: GET
   - URL: `http://your-app.com/api/service/test-connection`

2. **Get All Records**
   - Method: GET
   - URL: `http://your-app.com/api/service/clients`

3. **Create Record**
   - Method: POST
   - URL: `http://your-app.com/api/service/clients`
   - Body (JSON):
     ```json
     {
       "name": "New Client",
       "code": "CLIENT3"
     }
     ```

### Test with JavaScript

```javascript
// Test connection
fetch('/api/service/test-connection')
  .then(res => res.json())
  .then(data => console.log(data));

// Get all clients
fetch('/api/service/clients')
  .then(res => res.json())
  .then(data => console.log(data.data));

// Create new client
fetch('/api/service/clients', {
  method: 'POST',
  headers: {'Content-Type': 'application/json'},
  body: JSON.stringify({
    name: 'New Client',
    code: 'CLIENT3'
  })
})
.then(res => res.json())
.then(data => console.log(data));
```

---

## Performance Optimization

### 1. Enable Response Caching

Cache responses for better performance:

```php
use Illuminate\Support\Facades\Cache;

public function index(Request $request, $table)
{
    $cacheKey = 'service_' . $table . '_' . md5(json_encode($request->all()));

    return Cache::remember($cacheKey, 300, function() use ($request, $table) {
        // Make API call
        $response = Http::withHeaders($this->getHeaders())
            ->timeout(30)
            ->get("{$this->baseUrl}/{$table}", $request->all());

        return $this->handleResponse($response);
    });
}
```

### 2. Use Async Requests (for bulk operations)

```php
use Illuminate\Support\Facades\Http;

$responses = Http::pool(fn ($pool) => [
    $pool->get("{$this->baseUrl}/clients"),
    $pool->get("{$this->baseUrl}/amazon_review_accounts"),
    $pool->get("{$this->baseUrl}/amazon_review_projects"),
]);
```

### 3. Connection Pooling

Laravel HTTP client uses Guzzle which supports connection pooling automatically.

---

## FAQ

**Q: Can I use both local database AND external API?**
A: Yes! You can create separate controllers - one for local database access (existing controllers) and one for API access (UniversalServiceController).

**Q: What if the Claude API is down?**
A: The controller will return an error response. Implement retry logic or fallback mechanisms if needed.

**Q: Can I customize the request timeout?**
A: Yes, modify the `->timeout()` value in the controller methods.

**Q: How do I add authentication to the requests?**
A: Set `CLAUDE_API_TOKEN` in your `.env` file. The controller automatically adds it as a Bearer token.

**Q: Can I use this with other APIs (non-Claude)?**
A: Yes! Just update the `CLAUDE_API_BASE_URL` to point to any REST API that follows the same response format.

---

## Next Steps

1. ✅ Configure your `.env` file
2. ✅ Test the connection using `/api/service/test-connection`
3. ✅ Verify configuration with `/api/service/config`
4. ✅ Start making requests to your Claude API
5. ✅ Monitor logs for any errors
6. ✅ Implement error handling in your application

---

For questions or issues, please contact your system administrator.
