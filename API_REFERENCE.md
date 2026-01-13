# 📡 Admin API Reference

## Base URL
```
http://localhost:8000/admin
```

## Authentication
Semua endpoint memerlukan:
- Logged-in user
- User dengan role `admin`
- CSRF token untuk POST/PUT/DELETE requests

## 🔐 CSRF Token
```html
<meta name="csrf-token" content="{{ csrf_token() }}">
```

## Endpoints

### Dashboard
#### GET /admin
**Description**: Get admin dashboard dengan statistics dan charts

**Response**: View dengan data:
```json
{
  "totalUsers": 10,
  "totalAdmins": 2,
  "totalRegularUsers": 8,
  "totalFiles": 25,
  "totalSales": 5000,
  "filesUploaded": 25,
  "filesProcessed": 20,
  "filesFailed": 2,
  "totalFileSize": 52428800,
  "chartLabels": ["01 Jan", "02 Jan", ...],
  "chartGMV": [50000, 60000, ...],
  "chartItems": [100, 150, ...],
  "campaignLabels": ["Campaign A", "Campaign B", ...],
  "campaignGMV": [500000, 450000, ...],
  "userNames": ["User 1", "User 2", ...],
  "userFiles": [5, 3, ...]
}
```

---

### User Management

#### GET /admin/users
**Description**: List all users (paginated)

**Query Parameters**:
```
page=1          (default: 1)
per_page=15     (default: 15)
```

**Response**: Paginated list of users
```json
{
  "data": [
    {
      "id": 1,
      "name": "Admin",
      "email": "admin@email.com",
      "role": "admin",
      "uploaded_files_count": 5,
      "created_at": "2026-01-13T10:30:00Z"
    }
  ],
  "current_page": 1,
  "total": 10,
  "per_page": 15
}
```

#### GET /admin/users/create
**Description**: Show create user form

**Response**: HTML form view

#### POST /admin/users
**Description**: Create new user

**Request Body**:
```json
{
  "name": "John Doe",
  "email": "john@email.com",
  "password": "password123",
  "password_confirmation": "password123",
  "role": "user"
}
```

**Validation Rules**:
- `name`: required, string, max 255
- `email`: required, email, unique in users table
- `password`: required, min 8, confirmed
- `role`: required, in:user,admin

**Response**: Redirect to `/admin/users` with success message

#### GET /admin/users/{user}
**Description**: Show user details

**URL Parameters**:
```
{user} - User ID (integer)
```

**Response**: User detail view with:
- User info
- Upload statistics
- Recent uploads

#### GET /admin/users/{user}/edit
**Description**: Show edit user form

**URL Parameters**:
```
{user} - User ID (integer)
```

**Response**: HTML edit form

#### PUT /admin/users/{user}
**Description**: Update user

**URL Parameters**:
```
{user} - User ID (integer)
```

**Request Body**:
```json
{
  "name": "Updated Name",
  "email": "newemail@email.com",
  "password": "newpassword123",
  "password_confirmation": "newpassword123",
  "role": "admin"
}
```

**Validation Rules**:
- `name`: required, string, max 255
- `email`: required, email, unique (except current user)
- `password`: optional, min 8, confirmed
- `role`: required, in:user,admin

**Response**: Redirect with success message

**Constraints**:
- Cannot change current logged-in user's role to 'user'

#### DELETE /admin/users/{user}
**Description**: Delete user

**URL Parameters**:
```
{user} - User ID (integer)
```

**Request Header**:
```
X-CSRF-TOKEN: {csrf_token}
```

**Response**: Redirect with success/error message

**Constraints**:
- Cannot delete current logged-in user

#### PATCH /admin/users/{user}/role
**Description**: Update user role only

**URL Parameters**:
```
{user} - User ID (integer)
```

**Request Body**:
```json
{
  "role": "admin"
}
```

**Response**: JSON response with status

---

### File Management

#### GET /admin/files
**Description**: List all uploaded files (paginated)

**Query Parameters**:
```
page=1          (default: 1)
per_page=15     (default: 15)
status=pending  (optional: pending, processed, failed)
user_id=1       (optional: filter by user)
```

**Response**: Paginated list of files
```json
{
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "file_name": "uploads/abc123.csv",
      "original_name": "sales.csv",
      "file_size": 2048000,
      "row_count": 500,
      "status": "processed",
      "created_at": "2026-01-13T10:30:00Z",
      "user": {
        "id": 1,
        "name": "Admin"
      }
    }
  ],
  "current_page": 1,
  "total": 25,
  "per_page": 15
}
```

#### GET /admin/files/{file}
**Description**: Show file details

**URL Parameters**:
```
{file} - File ID (integer)
```

**Response**: File detail view with:
- File information
- Uploader details
- Data preview (10 rows)

#### PUT /admin/files/{file}
**Description**: Update file status

**URL Parameters**:
```
{file} - File ID (integer)
```

**Request Body (JSON)**:
```json
{
  "status": "processed"
}
```

**Request Header**:
```
X-CSRF-TOKEN: {csrf_token}
Content-Type: application/json
```

**Validation Rules**:
- `status`: required, in:pending,processed,failed

**Response**: 
- Success: JSON with updated data
- Error: 422 Validation error

**Example cURL**:
```bash
curl -X PUT http://localhost:8000/admin/files/1 \
  -H "X-CSRF-TOKEN: token" \
  -H "Content-Type: application/json" \
  -d '{"status": "processed"}'
```

#### DELETE /admin/files/{file}
**Description**: Delete file and storage

**URL Parameters**:
```
{file} - File ID (integer)
```

**Request Header**:
```
X-CSRF-TOKEN: {csrf_token}
```

**Response**: Redirect with success/error message

#### GET /admin/files/{file}/download
**Description**: Download file

**URL Parameters**:
```
{file} - File ID (integer)
```

**Response**: File download (attachment)

#### POST /admin/files/bulk-delete
**Description**: Delete multiple files

**Request Body (JSON)**:
```json
{
  "ids": [1, 2, 3]
}
```

**Request Header**:
```
X-CSRF-TOKEN: {csrf_token}
Content-Type: application/json
```

**Validation Rules**:
- `ids`: required, array
- `ids.*`: integer, exists in uploaded_files

**Response**: Redirect with success message

**Example cURL**:
```bash
curl -X POST http://localhost:8000/admin/files/bulk-delete \
  -H "X-CSRF-TOKEN: token" \
  -H "Content-Type: application/json" \
  -d '{"ids": [1, 2, 3]}'
```

---

## Error Responses

### 401 Unauthorized
```
User not logged in
Redirect to /login
```

### 403 Forbidden
```
User tidak admin
Status: 403
Message: "Unauthorized. Admin access only."
```

### 422 Unprocessable Entity
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "name": ["The name field is required."],
    "email": ["The email must be a valid email address."]
  }
}
```

### 404 Not Found
```
Resource not found
Redirect to /admin
```

---

## Response Codes

| Code | Meaning |
|------|---------|
| 200 | OK - Request successful |
| 201 | Created - Resource created |
| 302 | Redirect - Redirect response |
| 401 | Unauthorized - Not authenticated |
| 403 | Forbidden - No permission |
| 404 | Not Found - Resource not found |
| 422 | Unprocessable Entity - Validation error |
| 500 | Server Error - Internal error |

---

## Rate Limiting

No rate limiting applied to admin panel.

## Data Validation Examples

### Create User
```bash
curl -X POST http://localhost:8000/admin/users \
  -H "X-CSRF-TOKEN: token" \
  -d "name=John&email=john@email.com&password=pass123&password_confirmation=pass123&role=user"
```

### Update File Status
```bash
curl -X PUT http://localhost:8000/admin/files/1 \
  -H "X-CSRF-TOKEN: token" \
  -H "Content-Type: application/json" \
  -d '{"status": "processed"}'
```

### Bulk Delete Files
```bash
curl -X POST http://localhost:8000/admin/files/bulk-delete \
  -H "X-CSRF-TOKEN: token" \
  -H "Content-Type: application/json" \
  -d '{"ids": [1, 2, 3, 4, 5]}'
```

---

## Integration Notes

### CSRF Protection
```html
<!-- Get token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Use in JavaScript -->
const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

fetch('/admin/users', {
  method: 'POST',
  headers: {
    'X-CSRF-TOKEN': token,
    'Content-Type': 'application/json'
  },
  body: JSON.stringify(data)
});
```

### Pagination
```json
{
  "data": [...],
  "links": {
    "first": "http://localhost:8000/admin/users?page=1",
    "last": "http://localhost:8000/admin/users?page=10",
    "prev": "http://localhost:8000/admin/users?page=3",
    "next": "http://localhost:8000/admin/users?page=5"
  },
  "meta": {
    "current_page": 4,
    "from": 46,
    "last_page": 10,
    "per_page": 15,
    "to": 60,
    "total": 150
  }
}
```

---

**Last Updated**: January 13, 2026
**Version**: 1.0.0
