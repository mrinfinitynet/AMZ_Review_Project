# Universal Service API Documentation

## Overview

The Universal Service API is a **dynamic CRUD API** that allows you to interact with **any allowed table** using a **single unified endpoint structure**.

Instead of having separate endpoints for each model, you can use one service to manage all your data.

**Base URL:** `http://your-domain.com/api/service`

---

## Available Tables

The following tables are available through the service:

- `amazon_review_accounts`
- `amazon_review_projects`
- `amazon_review_project_histories`
- `clients`

---

## Quick Start Examples

### Get all accounts for Client1
```bash
GET /api/service/amazon_review_accounts?type=Client1
```

### Create a new project
```bash
POST /api/service/amazon_review_projects
{
  "type": "Client1",
  "project_id": "123",
  "status": "pending"
}
```

### Update a client
```bash
PUT /api/service/clients/5
{
  "name": "Updated Name"
}
```

### Delete an account
```bash
DELETE /api/service/amazon_review_accounts/10
```

---

## API Endpoints

### 1. Get Available Tables
**GET** `/api/service/tables`

Returns a list of all available tables with record counts.

**Response:**
```json
{
  "success": true,
  "data": {
    "tables": [
      {
        "name": "amazon_review_accounts",
        "model": "App\\Models\\AmazonReviewAccount",
        "record_count": 150
      },
      {
        "name": "amazon_review_projects",
        "model": "App\\Models\\AmazonReviewProject",
        "record_count": 500
      },
      {
        "name": "amazon_review_project_histories",
        "model": "App\\Models\\AmazonReviewProjectHistory",
        "record_count": 1200
      },
      {
        "name": "clients",
        "model": "App\\Models\\Client",
        "record_count": 5
      }
    ],
    "total": 4
  }
}
```

---

### 2. Get Table Structure
**GET** `/api/service/{table}/structure`

Returns the table schema (column names and types).

**Example:**
```bash
GET /api/service/clients/structure
```

**Response:**
```json
{
  "success": true,
  "data": {
    "table": "clients",
    "columns": [
      {
        "name": "id",
        "type": "bigint"
      },
      {
        "name": "name",
        "type": "string"
      },
      {
        "name": "code",
        "type": "string"
      },
      {
        "name": "key",
        "type": "string"
      },
      {
        "name": "is_active",
        "type": "boolean"
      },
      {
        "name": "created_at",
        "type": "datetime"
      },
      {
        "name": "updated_at",
        "type": "datetime"
      }
    ],
    "total_columns": 7
  }
}
```

---

### 3. Get All Records (with filtering)
**GET** `/api/service/{table}`

**Query Parameters:**
- Any field name for filtering
- `fields` - Select specific columns (comma-separated)
- `per_page` - Results per page (default: 15)
- `page` - Page number
- `no_pagination` - Get all results without pagination
- `order_by` - Field to order by (default: id)
- `order_direction` - asc or desc (default: desc)

**Examples:**

```bash
# Get all accounts for Client1
GET /api/service/amazon_review_accounts?type=Client1

# Get pending projects with pagination
GET /api/service/amazon_review_projects?status=pending&per_page=50

# Get all clients without pagination
GET /api/service/clients?no_pagination=true

# Get specific fields only
GET /api/service/clients?fields=id,name,code

# Order by created_at ascending
GET /api/service/amazon_review_projects?order_by=created_at&order_direction=asc
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "account_name": "John Doe",
      "account_email": "john@example.com",
      "type": "Client1"
    }
  ],
  "pagination": {
    "total": 100,
    "per_page": 15,
    "current_page": 1,
    "last_page": 7,
    "from": 1,
    "to": 15
  }
}
```

---

### 4. Get Single Record
**GET** `/api/service/{table}/{id}`

**Query Parameters:**
- `fields` - Select specific columns (comma-separated)

**Examples:**

```bash
# Get client by ID
GET /api/service/clients/5

# Get specific fields only
GET /api/service/clients/5?fields=id,name,code
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 5,
    "name": "Client Name",
    "code": "Client1",
    "is_active": true
  }
}
```

---

### 5. Create Record
**POST** `/api/service/{table}`

**Request Body:**
Send any valid fields for the table.

**Examples:**

```bash
# Create account
POST /api/service/amazon_review_accounts
{
  "account_name": "Jane Smith",
  "account_email": "jane@example.com",
  "account_password": "password123",
  "type": "Client2",
  "total_review": 0
}

# Create project
POST /api/service/amazon_review_projects
{
  "type": "Client1",
  "project_id": "456",
  "book_asin": "B07XYZ1234",
  "account_id": "ACC001",
  "review_title": "Great product!",
  "review_description": "Amazing quality...",
  "rating": 5,
  "status": "pending"
}

# Create client
POST /api/service/clients
{
  "name": "New Client",
  "code": "Client3",
  "description": "Third client",
  "is_active": true,
  "sort_order": 3
}
```

**Response:**
```json
{
  "success": true,
  "message": "Record created successfully",
  "data": {
    "id": 15,
    "name": "New Client",
    "code": "Client3",
    "is_active": true,
    "created_at": "2025-01-15T10:30:00.000000Z"
  }
}
```

---

### 6. Update Record
**PUT/PATCH** `/api/service/{table}/{id}`

**Request Body:**
Send fields to update (partial updates supported).

**Examples:**

```bash
# Update account
PUT /api/service/amazon_review_accounts/10
{
  "account_name": "Updated Name",
  "total_review": 25
}

# Update project status
PATCH /api/service/amazon_review_projects/50
{
  "status": "approved"
}

# Update client
PUT /api/service/clients/3
{
  "is_active": false
}
```

**Response:**
```json
{
  "success": true,
  "message": "Record updated successfully",
  "data": {
    "id": 10,
    "account_name": "Updated Name",
    "total_review": 25,
    "updated_at": "2025-01-15T11:00:00.000000Z"
  }
}
```

---

### 7. Delete Record
**DELETE** `/api/service/{table}/{id}`

**Example:**
```bash
DELETE /api/service/amazon_review_accounts/10
```

**Response:**
```json
{
  "success": true,
  "message": "Record deleted successfully"
}
```

---

### 8. Bulk Delete Records
**POST** `/api/service/{table}/bulk-delete`

**Request Body:**
```json
{
  "ids": [1, 2, 3, 4, 5]
}
```

**Response:**
```json
{
  "success": true,
  "message": "Successfully deleted 5 records"
}
```

---

### 9. Bulk Update Records
**POST** `/api/service/{table}/bulk-update`

**Request Body:**
```json
{
  "ids": [1, 2, 3, 4, 5],
  "data": {
    "type": "Client1",
    "status": "approved"
  }
}
```

**Response:**
```json
{
  "success": true,
  "message": "Successfully updated 5 records"
}
```

---

### 10. Get Record Count
**GET** `/api/service/{table}/count`

Get the total count of records (with optional filtering).

**Examples:**

```bash
# Total count
GET /api/service/amazon_review_accounts/count

# Count with filter
GET /api/service/amazon_review_accounts/count?type=Client1

# Count pending projects
GET /api/service/amazon_review_projects/count?status=pending
```

**Response:**
```json
{
  "success": true,
  "data": {
    "table": "amazon_review_accounts",
    "count": 75
  }
}
```

---

### 11. Custom Query (Advanced)
**POST** `/api/service/{table}/query`

Execute complex queries with multiple conditions.

**Request Body:**
```json
{
  "conditions": [
    {
      "field": "type",
      "operator": "=",
      "value": "Client1"
    },
    {
      "field": "status",
      "operator": "=",
      "value": "pending"
    },
    {
      "field": "rating",
      "operator": ">=",
      "value": 4
    }
  ],
  "order_by": "created_at",
  "order_direction": "desc",
  "limit": 100
}
```

**Supported Operators:**
- `=` - Equal
- `!=` - Not equal
- `>` - Greater than
- `>=` - Greater than or equal
- `<` - Less than
- `<=` - Less than or equal
- `like` - LIKE search
- `not like` - NOT LIKE

**Response:**
```json
{
  "success": true,
  "data": [
    { /* records matching conditions */ }
  ],
  "total": 25
}
```

---

## Real-World Use Cases

### Use Case 1: Get all pending reviews for a client
```bash
GET /api/service/amazon_review_projects?type=Client1&status=pending&no_pagination=true
```

### Use Case 2: Update all projects to "delete" status
```bash
# First, get IDs
GET /api/service/amazon_review_projects?project_id=123&no_pagination=true

# Then bulk update
POST /api/service/amazon_review_projects/bulk-update
{
  "ids": [1, 2, 3, 4, 5],
  "data": {
    "status": "delete"
  }
}
```

### Use Case 3: Create multiple accounts at once
```bash
# Create account 1
POST /api/service/amazon_review_accounts
{
  "account_name": "User 1",
  "account_email": "user1@example.com",
  "type": "Client1"
}

# Create account 2
POST /api/service/amazon_review_accounts
{
  "account_name": "User 2",
  "account_email": "user2@example.com",
  "type": "Client1"
}
```

### Use Case 4: Get project statistics
```bash
# Get total projects
GET /api/service/amazon_review_projects/count

# Get pending count
GET /api/service/amazon_review_projects/count?status=pending

# Get approved count
GET /api/service/amazon_review_projects/count?status=approved

# Get client-specific count
GET /api/service/amazon_review_projects/count?type=Client1
```

### Use Case 5: Complex query with multiple conditions
```bash
POST /api/service/amazon_review_projects/query
{
  "conditions": [
    {
      "field": "type",
      "operator": "=",
      "value": "Client1"
    },
    {
      "field": "status",
      "operator": "!=",
      "value": "delete"
    },
    {
      "field": "created_at",
      "operator": ">=",
      "value": "2025-01-01"
    }
  ],
  "order_by": "created_at",
  "order_direction": "desc",
  "limit": 50
}
```

---

## Response Format

### Success Response
```json
{
  "success": true,
  "message": "Optional message",
  "data": { /* data or array */ },
  "total": 100 // Optional
}
```

### Success with Pagination
```json
{
  "success": true,
  "data": [ /* records */ ],
  "pagination": {
    "total": 100,
    "per_page": 15,
    "current_page": 1,
    "last_page": 7,
    "from": 1,
    "to": 15
  }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error description",
  "errors": { /* validation errors if applicable */ }
}
```

---

## HTTP Status Codes

- `200` - Success
- `201` - Created
- `400` - Bad request
- `404` - Not found
- `422` - Validation failed
- `500` - Server error

---

## Security & Permissions

The Universal Service API only allows access to **pre-defined tables** listed in the `$modelMap` array. Any attempt to access unlisted tables will return a 404 error.

**Allowed Tables:**
- amazon_review_accounts
- amazon_review_projects
- amazon_review_project_histories
- clients

To add more tables, update the `$modelMap` in `UniversalServiceController.php`:

```php
protected $modelMap = [
    'table_name' => \App\Models\ModelName::class,
];
```

---

## Integration Examples

### JavaScript (Fetch API)

```javascript
// Get all clients
fetch('http://your-domain.com/api/service/clients')
  .then(response => response.json())
  .then(data => console.log(data));

// Create a new account
fetch('http://your-domain.com/api/service/amazon_review_accounts', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    account_name: 'John Doe',
    account_email: 'john@example.com',
    type: 'Client1'
  })
})
.then(response => response.json())
.then(data => console.log(data));

// Update a record
fetch('http://your-domain.com/api/service/clients/5', {
  method: 'PUT',
  headers: {
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    name: 'Updated Name'
  })
})
.then(response => response.json())
.then(data => console.log(data));
```

### PHP (cURL)

```php
// Get all records
$ch = curl_init('http://your-domain.com/api/service/clients');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
$data = json_decode($response, true);

// Create record
$data = [
    'account_name' => 'John Doe',
    'account_email' => 'john@example.com',
    'type' => 'Client1'
];

$ch = curl_init('http://your-domain.com/api/service/amazon_review_accounts');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
$response = curl_exec($ch);
curl_close($ch);
```

### Python (Requests)

```python
import requests

# Get all records
response = requests.get('http://your-domain.com/api/service/clients')
data = response.json()

# Create record
payload = {
    'account_name': 'John Doe',
    'account_email': 'john@example.com',
    'type': 'Client1'
}
response = requests.post(
    'http://your-domain.com/api/service/amazon_review_accounts',
    json=payload
)
data = response.json()

# Update record
payload = {'name': 'Updated Name'}
response = requests.put(
    'http://your-domain.com/api/service/clients/5',
    json=payload
)
data = response.json()
```

---

## Benefits of Universal Service API

1. **Single Endpoint Structure** - One consistent API pattern for all tables
2. **Dynamic Filtering** - Filter by any field without custom code
3. **Flexible Queries** - Build complex queries on the fly
4. **No Code Changes** - Add new tables by just updating the model map
5. **Consistent Response Format** - All endpoints return the same structure
6. **Built-in Pagination** - Automatic pagination support
7. **Field Selection** - Get only the fields you need
8. **Bulk Operations** - Update or delete multiple records at once
9. **Table Introspection** - Get table structure dynamically
10. **Easy Integration** - Works with any programming language

---

## Comparison: Dedicated API vs Universal Service API

### Dedicated API (Traditional)
```bash
GET /api/amazon-review-accounts
GET /api/amazon-review-projects
GET /api/clients
```

### Universal Service API (New)
```bash
GET /api/service/amazon_review_accounts
GET /api/service/amazon_review_projects
GET /api/service/clients
```

**You can use BOTH approaches!** The Universal Service API is an addition, not a replacement. Use whichever fits your needs.

---

## Tips & Best Practices

1. **Use `no_pagination=true` for small datasets** - Faster when you need all records
2. **Select specific fields** - Use `?fields=id,name` to reduce payload size
3. **Use bulk operations** - More efficient than multiple single requests
4. **Check table structure first** - Use `/structure` endpoint to know available fields
5. **Use custom queries for complex filters** - More powerful than query parameters
6. **Get counts before fetching** - Use `/count` to know total records
7. **List tables first** - Use `/tables` endpoint to see what's available

---

For questions or issues, please contact your system administrator.
