# Universal Service API - Quick Start (No Authentication)

## Simple Setup - 3 Steps

### Step 1: Set Your API URL in `.env`

```env
CLAUDE_API_BASE_URL=http://127.0.0.1:8000/api
```

**Change this to your actual Claude server URL:**
- Local: `http://127.0.0.1:8000/api`
- Production: `https://api.yourcompany.com/api`

### Step 2: Test Connection

```bash
GET http://your-app.com/api/service/test-connection
```

**Success Response:**
```json
{
  "success": true,
  "message": "Connection successful"
}
```

### Step 3: Start Using the API

```bash
# Get all clients
GET /api/service/clients

# Get single client
GET /api/service/clients/5

# Create new client
POST /api/service/clients
Body: {"name": "New Client", "code": "CLIENT3"}

# Update client
PUT /api/service/clients/5
Body: {"name": "Updated Name"}

# Delete client
DELETE /api/service/clients/5
```

---

## How It Works

```
Your App Request → UniversalServiceController → Claude API Server → Database
        ↓                                              ↓
    Returns ←──────────────────────────────────────  Response
```

**Example:**

Your request:
```bash
GET /api/service/clients?type=Client1
```

Forwards to:
```bash
GET http://127.0.0.1:8000/api/clients?type=Client1
```

---

## Available Tables

- `amazon_review_accounts`
- `amazon_review_projects`
- `amazon_review_project_histories`
- `clients`

---

## All Endpoints

| Method | Endpoint | Forwards To |
|--------|----------|-------------|
| GET | `/api/service/{table}` | `{base_url}/{table}` |
| GET | `/api/service/{table}/{id}` | `{base_url}/{table}/{id}` |
| POST | `/api/service/{table}` | `{base_url}/{table}` |
| PUT | `/api/service/{table}/{id}` | `{base_url}/{table}/{id}` |
| DELETE | `/api/service/{table}/{id}` | `{base_url}/{table}/{id}` |
| POST | `/api/service/{table}/bulk-delete` | `{base_url}/{table}/bulk-delete` |
| POST | `/api/service/{table}/bulk-update` | `{base_url}/{table}/bulk-update` |
| GET | `/api/service/{table}/structure` | `{base_url}/{table}/structure` |
| GET | `/api/service/{table}/count` | `{base_url}/{table}/count` |
| POST | `/api/service/{table}/query` | `{base_url}/{table}/query` |

---

## Examples

### Get All Accounts
```bash
GET /api/service/amazon_review_accounts?type=Client1
```

### Create Project
```bash
POST /api/service/amazon_review_projects
{
  "type": "Client1",
  "project_id": "123",
  "status": "pending",
  "rating": 5
}
```

### Bulk Update
```bash
POST /api/service/amazon_review_projects/bulk-update
{
  "ids": [1, 2, 3],
  "data": {
    "status": "approved"
  }
}
```

### Custom Query
```bash
POST /api/service/amazon_review_projects/query
{
  "conditions": [
    {"field": "type", "operator": "=", "value": "Client1"},
    {"field": "status", "operator": "=", "value": "pending"}
  ]
}
```

---

## JavaScript Example

```javascript
// Test connection
fetch('/api/service/test-connection')
  .then(res => res.json())
  .then(data => console.log(data));

// Get all clients
fetch('/api/service/clients')
  .then(res => res.json())
  .then(data => console.log(data.data));

// Create new record
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

## Configuration Check

```bash
GET /api/service/config
```

**Response:**
```json
{
  "success": true,
  "data": {
    "base_url": "http://127.0.0.1:8000/api",
    "allowed_tables": ["amazon_review_accounts", "amazon_review_projects", ...],
    "timeout": 30,
    "status": "ready"
  }
}
```

---

## That's It!

No authentication needed. Just set your `CLAUDE_API_BASE_URL` and start making requests!

**Need more details?** See `SERVICE_API_SETUP_GUIDE.md` for advanced configuration.
