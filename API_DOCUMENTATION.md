# Amazon Review Management API Documentation

Complete RESTful API for managing Amazon Review Accounts, Projects, Project History, and Clients.

**Base URL:** `http://your-domain.com/api`

---

## Table of Contents

1. [Amazon Review Accounts API](#amazon-review-accounts-api)
2. [Amazon Review Projects API](#amazon-review-projects-api)
3. [Amazon Review Project History API](#amazon-review-project-history-api)
4. [Clients API](#clients-api)
5. [Response Format](#response-format)
6. [Error Handling](#error-handling)
7. [Filtering & Pagination](#filtering--pagination)

---

## Amazon Review Accounts API

### Base Endpoint: `/api/amazon-review-accounts`

### 1. Get All Accounts (with filtering)
**GET** `/api/amazon-review-accounts`

**Query Parameters:**
- `type` - Filter by client type (e.g., Client1, Client2)
- `account_name` - Filter by account name (LIKE search)
- `account_email` - Filter by email (LIKE search)
- `per_page` - Results per page (default: 15)
- `page` - Page number
- `no_pagination` - Return all results without pagination
- Any field name for filtering

**Example:**
```bash
GET /api/amazon-review-accounts?type=Client1&per_page=20
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "account_name": "John Doe",
      "account_id": "12345",
      "account_email": "john@example.com",
      "type": "Client1",
      "total_review": 10,
      "last_checking": "Success",
      "created_at": "2025-01-01T00:00:00.000000Z",
      "updated_at": "2025-01-15T10:30:00.000000Z"
    }
  ],
  "pagination": {
    "total": 100,
    "per_page": 20,
    "current_page": 1,
    "last_page": 5,
    "from": 1,
    "to": 20
  }
}
```

### 2. Get Single Account
**GET** `/api/amazon-review-accounts/{id}`

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "account_name": "John Doe",
    "account_id": "12345",
    "account_email": "john@example.com",
    "account_password": "password123",
    "type": "Client1",
    "total_review": 10,
    "last_checking": null
  }
}
```

### 3. Create Account
**POST** `/api/amazon-review-accounts`

**Request Body:**
```json
{
  "account_name": "Jane Smith",
  "account_id": "67890",
  "account_email": "jane@example.com",
  "account_password": "secure_password",
  "type": "Client2",
  "total_review": 0
}
```

**Response:**
```json
{
  "success": true,
  "message": "Account created successfully",
  "data": { /* account object */ }
}
```

### 4. Update Account
**PUT/PATCH** `/api/amazon-review-accounts/{id}`

**Request Body:**
```json
{
  "account_name": "Jane Doe",
  "total_review": 5
}
```

### 5. Delete Account
**DELETE** `/api/amazon-review-accounts/{id}`

**Response:**
```json
{
  "success": true,
  "message": "Account deleted successfully"
}
```

### 6. Bulk Delete Accounts
**POST** `/api/amazon-review-accounts/bulk-delete`

**Request Body:**
```json
{
  "ids": [1, 2, 3, 4, 5]
}
```

### 7. Bulk Update Accounts
**POST** `/api/amazon-review-accounts/bulk-update`

**Request Body:**
```json
{
  "ids": [1, 2, 3],
  "data": {
    "type": "Client1",
    "total_review": 10
  }
}
```

---

## Amazon Review Projects API

### Base Endpoint: `/api/amazon-review-projects`

### 1. Get All Projects (with filtering)
**GET** `/api/amazon-review-projects`

**Query Parameters:**
- `type` - Filter by client type
- `status` - Filter by status (pending, approved, rejected, delete)
- `project_id` - Filter by project ID
- `book_asin` - Filter by book ASIN
- `account_id` - Filter by account ID
- `per_page` - Results per page
- `order_by` - Order by field (default: id)
- `order_direction` - asc/desc (default: desc)
- `no_pagination` - Return all results

**Example:**
```bash
GET /api/amazon-review-projects?type=Client1&status=pending&per_page=50
```

### 2. Get Grouped Projects
**GET** `/api/amazon-review-projects/grouped?type=Client1`

Returns projects grouped by `project_id` (useful for dashboard views).

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "project_id": "123",
      "book_asin": "B07XYZ1234",
      "review_link": "https://amazon.com/...",
      "created_at": "2025-01-01T00:00:00.000000Z",
      "updated_at": "2025-01-15T10:30:00.000000Z",
      "reviews": [
        {
          "id": 1,
          "account_id": "12345",
          "review_title": "Great book!",
          "review_description": "This book is amazing...",
          "rating": 5,
          "status": "pending"
        }
      ]
    }
  ],
  "total": 10
}
```

### 3. Create Project
**POST** `/api/amazon-review-projects`

**Request Body:**
```json
{
  "type": "Client1",
  "project_id": "123",
  "book_asin": "B07XYZ1234",
  "account_id": "12345",
  "review_title": "Excellent Read",
  "review_description": "This book exceeded all expectations...",
  "rating": 5,
  "status": "pending"
}
```

### 4. Update Project Status
**POST** `/api/amazon-review-projects/update-status`

Update all reviews for a specific project_id.

**Request Body:**
```json
{
  "project_id": "123",
  "status": "delete"
}
```

### 5. Bulk Operations
**POST** `/api/amazon-review-projects/bulk-delete`
**POST** `/api/amazon-review-projects/bulk-update`

Same format as Accounts bulk operations.

---

## Amazon Review Project History API

### Base Endpoint: `/api/amazon-review-project-histories`

### 1. Get All History Records
**GET** `/api/amazon-review-project-histories`

**Query Parameters:**
- `type` - Filter by client type
- `status` - Filter by status
- `review_id` - Filter by review ID
- `project_id` - Filter by project ID
- `per_page`, `order_by`, `order_direction`, etc.

### 2. Create History Record
**POST** `/api/amazon-review-project-histories`

**Request Body:**
```json
{
  "review_id": 1,
  "project_id": "123",
  "account_id": "12345",
  "rating": 5,
  "msg": "Review submitted successfully",
  "status": "approved",
  "type": "Client1"
}
```

### 3. Clear History by Type
**POST** `/api/amazon-review-project-histories/clear`

**Request Body:**
```json
{
  "type": "Client1"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Successfully cleared 25 history records for type Client1"
}
```

### 4. Bulk Operations
**POST** `/api/amazon-review-project-histories/bulk-delete`
**POST** `/api/amazon-review-project-histories/bulk-update`

---

## Clients API

### Base Endpoint: `/api/clients`

### 1. Get All Clients
**GET** `/api/clients`

**Query Parameters:**
- `name` - Filter by name
- `code` - Filter by code
- `is_active` - Filter by active status (0/1)
- `active_only` - Get only active clients (true/false)
- `per_page`, `order_by`, `order_direction`

### 2. Get Active Clients Only
**GET** `/api/clients/active`

Returns all active clients sorted by sort_order.

### 3. Get Client by Code
**GET** `/api/clients/by-code/{code}`

**Example:**
```bash
GET /api/clients/by-code/Client1
```

### 4. Get Client by Access Key
**GET** `/api/clients/by-key/{key}`

**Example:**
```bash
GET /api/clients/by-key/ABCD-EFGH-IJKL
```

### 5. Create Client
**POST** `/api/clients`

**Request Body:**
```json
{
  "name": "New Client",
  "code": "Client3",
  "description": "Third client for review management",
  "is_active": true,
  "sort_order": 3,
  "generate_key": true
}
```

### 6. Verify Access Key
**POST** `/api/clients/verify-key`

Verifies a client's access key and tracks access.

**Request Body:**
```json
{
  "key": "ABCD-EFGH-IJKL"
}
```

**Response (Success):**
```json
{
  "success": true,
  "message": "Access key verified successfully",
  "data": {
    "id": 1,
    "name": "Client Name",
    "code": "Client1",
    "key": "ABCD-EFGH-IJKL",
    "is_active": true,
    "access_count": 15,
    "last_accessed_at": "2025-01-15T10:30:00.000000Z"
  }
}
```

**Response (Invalid):**
```json
{
  "success": false,
  "message": "Invalid access key"
}
```

### 7. Generate New Access Key
**POST** `/api/clients/{id}/generate-key`

Generates a new unique access key for the client.

### 8. Remove Access Key
**POST** `/api/clients/{id}/remove-key`

Removes the access key from the client.

### 9. Toggle Client Status
**POST** `/api/clients/{id}/toggle-status`

Toggles the is_active status between true/false.

### 10. Track Client Access
**POST** `/api/clients/{id}/track-access`

Manually track an access event (increments access_count, updates last_accessed_at).

---

## Response Format

### Success Response
```json
{
  "success": true,
  "data": { /* resource or array of resources */ },
  "message": "Optional success message"
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error description",
  "error": "Detailed error message",
  "errors": { /* validation errors if applicable */ }
}
```

---

## Error Handling

**HTTP Status Codes:**
- `200` - Success
- `201` - Resource created
- `400` - Bad request
- `404` - Resource not found
- `422` - Validation failed
- `500` - Server error

**Validation Error Example:**
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "account_email": [
      "The account email must be a valid email address."
    ],
    "rating": [
      "The rating must be between 1 and 5."
    ]
  }
}
```

---

## Filtering & Pagination

### Dynamic Filtering

All GET endpoints support dynamic filtering by any field:

```bash
# Filter by type
GET /api/amazon-review-accounts?type=Client1

# Filter by multiple fields
GET /api/amazon-review-projects?type=Client2&status=pending&rating=5

# LIKE search (default)
GET /api/clients?name=John  # Searches for names containing "John"

# Exact match
GET /api/clients?name=John&name_exact=true
```

### Pagination

```bash
# Page 2 with 50 results per page
GET /api/amazon-review-accounts?page=2&per_page=50

# Get all results without pagination
GET /api/amazon-review-accounts?no_pagination=true
```

### Ordering

```bash
# Order by created_at descending
GET /api/amazon-review-projects?order_by=created_at&order_direction=desc

# Order by rating ascending
GET /api/amazon-review-projects?order_by=rating&order_direction=asc
```

---

## Example Use Cases

### 1. Get all pending projects for Client1
```bash
GET /api/amazon-review-projects?type=Client1&status=pending&no_pagination=true
```

### 2. Create multiple reviews for a project
```bash
# Review 1
POST /api/amazon-review-projects
{
  "type": "Client1",
  "project_id": "123",
  "account_id": "ACC001",
  "review_title": "Great!",
  "rating": 5,
  "status": "pending"
}

# Review 2
POST /api/amazon-review-projects
{
  "type": "Client1",
  "project_id": "123",
  "account_id": "ACC002",
  "review_title": "Excellent!",
  "rating": 5,
  "status": "pending"
}
```

### 3. Stop all reviews for a project
```bash
POST /api/amazon-review-projects/update-status
{
  "project_id": "123",
  "status": "delete"
}
```

### 4. Verify client access and get client data
```bash
POST /api/clients/verify-key
{
  "key": "ABCD-EFGH-IJKL"
}
```

### 5. Get project statistics
```bash
# Get grouped projects with all reviews
GET /api/amazon-review-projects/grouped?type=Client1

# Get history for a specific project
GET /api/amazon-review-project-histories?project_id=123&type=Client1&no_pagination=true
```

---

## Notes

- All timestamps are in UTC format (ISO 8601)
- Access keys are automatically converted to uppercase
- Bulk operations return the count of affected records
- Filtering supports both exact match and LIKE search
- All models support mass assignment via fillable fields
- Client access is tracked automatically when using verify-key endpoint

---

## Testing with cURL

### Create an account
```bash
curl -X POST http://your-domain.com/api/amazon-review-accounts \
  -H "Content-Type: application/json" \
  -d '{
    "account_name": "Test User",
    "account_email": "test@example.com",
    "type": "Client1"
  }'
```

### Get all accounts for Client1
```bash
curl -X GET "http://your-domain.com/api/amazon-review-accounts?type=Client1"
```

### Verify client key
```bash
curl -X POST http://your-domain.com/api/clients/verify-key \
  -H "Content-Type: application/json" \
  -d '{
    "key": "ABCD-EFGH-IJKL"
  }'
```

---

For questions or issues, please contact your system administrator.
