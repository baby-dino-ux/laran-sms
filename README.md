<div align="center">

# 🎓 Scholarship Management System
### *REST API — Laravel 12 + Sanctum*

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![Sanctum](https://img.shields.io/badge/Auth-Sanctum-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com/docs/sanctum)

A full-featured REST API for managing scholarships, student applications, document submissions, awards, and notifications — built for the **IT Department Scholarship Management System** project.

</div>

---

## 📌 Table of Contents

- [Overview](#-overview)
- [Features](#-features)
- [Tech Stack](#-tech-stack)
- [Getting Started](#-getting-started)
- [Environment Setup](#-environment-setup)
- [Database Schema](#-database-schema)
- [API Endpoints](#-api-endpoints)
- [Authentication](#-authentication)
- [Roles & Permissions](#-roles--permissions)
- [Postman Collection](#-postman-collection)
- [Application Workflow](#-application-workflow)

---

## 🧾 Overview

The **Scholarship Management System (SMS)** is a Laravel-based REST API that handles the full lifecycle of scholarship management — from posting scholarships and accepting student applications, to reviewing, approving, awarding, and notifying scholars.

All endpoints are protected using **Laravel Sanctum** token-based authentication. Admins have full control, while Students can manage their own applications, documents, and notifications.

---

## ✅ Features

| Module | Capabilities |
|--------|-------------|
| 🔐 **Authentication** | Register, Login, Logout, View authenticated user |
| 👤 **User Profile** | View & update profile info, Upload profile picture |
| 🛡️ **User Management** *(Admin)* | List, view, update, delete users; Assign roles |
| 🎓 **Scholarships** | Full CRUD, Set eligibility criteria per scholarship |
| 📋 **Applications** | Create, submit, review, approve, reject with status tracking |
| 📁 **Documents** | Upload, download, and delete supporting documents |
| 🏆 **Awards** | Grant awards, view award history, trigger notifications |
| 🔔 **Notifications** | Send, read, mark-all-read, delete in-app notifications |
| 📊 **Reports** *(Admin)* | Dashboard summary, application stats, award reports |

---

## 🛠 Tech Stack

- **Framework:** Laravel 12
- **Language:** PHP 8.2+
- **Authentication:** Laravel Sanctum (Token-based)
- **Database:** MySQL 8.0+
- **Local Server:** XAMPP (Apache + MySQL)
- **API Testing:** Postman

---

## 🚀 Getting Started

### Requirements

- PHP 8.2+
- Composer
- MySQL (via XAMPP or standalone)
- Git

### Installation

```bash
# 1. Clone the repository
git clone https://github.com/baby-dino-ux/laran-sms.git
cd laran-sms

# 2. Install PHP dependencies
composer install

# 3. Copy the environment file
cp .env.example .env

# 4. Generate the application key
php artisan key:generate

# 5. Run database migrations
php artisan migrate

# 6. Create storage symlink (for file uploads)
php artisan storage:link

# 7. Start the development server
php artisan serve
```

The API will be available at: `http://localhost:8000/api`

---

## ⚙️ Environment Setup

Edit your `.env` file and configure the database connection:

```env
APP_NAME="Scholarship Management System"
APP_ENV=local
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=scholarship_db
DB_USERNAME=root
DB_PASSWORD=
```

---

## 🗄️ Database Schema

The system uses **10 migrations** creating the following tables:

| Table | Description |
|-------|-------------|
| `users` | Students and Admins with profile fields |
| `scholarships` | Scholarship programs with eligibility criteria |
| `applications` | Student applications with status lifecycle |
| `documents` | Uploaded supporting files linked to applications |
| `awards` | Granted scholarship awards |
| `sms_notifications` | In-app notifications for users |
| `personal_access_tokens` | Sanctum authentication tokens |
| `cache` | Laravel cache storage |
| `jobs` | Laravel queue jobs |
| `sessions` | User session storage |

---

## 📡 API Endpoints

> **Base URL:** `http://localhost:8000/api`  
> **🔓 Public** = No token required | **🔒 Protected** = Requires `Authorization: Bearer {token}` | **🛡️ Admin** = Admin role only

---

### 🔐 Authentication

| Method | Endpoint | Access | Description |
|--------|----------|--------|-------------|
| `POST` | `/api/register` | 🔓 Public | Register a new user |
| `POST` | `/api/login` | 🔓 Public | Login and receive Bearer token |
| `POST` | `/api/logout` | 🔒 Protected | Logout and revoke token |
| `GET` | `/api/me` | 🔒 Protected | Get authenticated user info |

**Register body:**
```json
{
  "first_name": "Juan",
  "last_name": "Dela Cruz",
  "email": "juan@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "role": "Student"
}
```

**Login body:**
```json
{
  "email": "juan@example.com",
  "password": "password123"
}
```

---

### 👤 User Profile

| Method | Endpoint | Access | Description |
|--------|----------|--------|-------------|
| `GET` | `/api/profile` | 🔒 Protected | View own profile |
| `PUT` | `/api/profile` | 🔒 Protected | Update own profile |
| `POST` | `/api/profile/picture` | 🔒 Protected | Upload profile picture |

---

### 🛡️ User Management (Admin)

| Method | Endpoint | Access | Description |
|--------|----------|--------|-------------|
| `GET` | `/api/users` | 🛡️ Admin | List all users (paginated) |
| `GET` | `/api/users/{id}` | 🛡️ Admin | Get a specific user |
| `PUT` | `/api/users/{id}` | 🛡️ Admin | Update a user |
| `DELETE` | `/api/users/{id}` | 🛡️ Admin | Delete a user |
| `PATCH` | `/api/users/{id}/role` | 🛡️ Admin | Assign role (`Admin` / `Student`) |

---

### 🎓 Scholarships

| Method | Endpoint | Access | Description |
|--------|----------|--------|-------------|
| `GET` | `/api/scholarships` | 🔒 Protected | List all scholarships |
| `GET` | `/api/scholarships/{id}` | 🔒 Protected | Get a scholarship |
| `POST` | `/api/scholarships` | 🛡️ Admin | Create a scholarship |
| `PUT` | `/api/scholarships/{id}` | 🛡️ Admin | Update a scholarship |
| `DELETE` | `/api/scholarships/{id}` | 🛡️ Admin | Delete a scholarship |
| `PUT` | `/api/scholarships/{id}/eligibility` | 🛡️ Admin | Set eligibility criteria |

**Create scholarship body:**
```json
{
  "scholarship_name": "CHED Merit Scholarship",
  "description": "For academically excellent students.",
  "amount": 20000,
  "slots": 50,
  "deadline": "2025-12-31",
  "status": "active",
  "eligibility_criteria": {
    "gpa": 1.75,
    "year_level": ["3rd", "4th"]
  }
}
```

---

### 📋 Applications

| Method | Endpoint | Access | Description |
|--------|----------|--------|-------------|
| `GET` | `/api/applications` | 🔒 Protected | List applications (own or all for Admin) |
| `POST` | `/api/applications` | 🔒 Protected | Create a new application |
| `GET` | `/api/applications/{id}` | 🔒 Protected | Get application details |
| `PUT` | `/api/applications/{id}` | 🔒 Protected | Update a draft application |
| `GET` | `/api/applications/{id}/status` | 🔒 Protected | Check application status |
| `POST` | `/api/applications/{id}/submit` | 🔒 Protected | Submit application for review |
| `POST` | `/api/applications/{id}/review` | 🛡️ Admin | Mark application as under review |
| `POST` | `/api/applications/{id}/approve` | 🛡️ Admin | Approve an application |
| `POST` | `/api/applications/{id}/reject` | 🛡️ Admin | Reject an application |

**Status values:** `draft` → `submitted` → `under_review` → `approved` / `rejected`

---

### 📁 Documents

| Method | Endpoint | Access | Description |
|--------|----------|--------|-------------|
| `GET` | `/api/documents` | 🔒 Protected | List documents (own or all for Admin) |
| `POST` | `/api/documents` | 🔒 Protected | Upload a document (`multipart/form-data`) |
| `GET` | `/api/documents/{id}` | 🔒 Protected | Get document info |
| `GET` | `/api/documents/{id}/download` | 🔒 Protected | Download the file |
| `DELETE` | `/api/documents/{id}` | 🔒 Protected | Delete a document |

**Upload form fields:** `file` (required), `document_type` (e.g. `transcript`), `application_id` (optional)

---

### 🏆 Awards

| Method | Endpoint | Access | Description |
|--------|----------|--------|-------------|
| `GET` | `/api/awards` | 🔒 Protected | List awards (own or all for Admin) |
| `POST` | `/api/awards` | 🛡️ Admin | Grant a scholarship award |
| `GET` | `/api/awards/history` | 🔒 Protected | Full award history |
| `GET` | `/api/awards/{id}` | 🔒 Protected | Get award details |
| `POST` | `/api/awards/{id}/notify` | 🛡️ Admin | Send award notification to scholar |

**Grant award body:**
```json
{
  "user_id": 2,
  "scholarship_id": 1,
  "application_id": 1,
  "amount_granted": 20000,
  "award_date": "2025-06-15",
  "notes": "First semester award."
}
```

---

### 🔔 Notifications

| Method | Endpoint | Access | Description |
|--------|----------|--------|-------------|
| `GET` | `/api/notifications` | 🔒 Protected | List notifications |
| `GET` | `/api/notifications/{id}` | 🔒 Protected | Get notification |
| `POST` | `/api/notifications/send` | 🛡️ Admin | Send notification to a user |
| `PATCH` | `/api/notifications/{id}/read` | 🔒 Protected | Mark as read |
| `PATCH` | `/api/notifications/read-all` | 🔒 Protected | Mark all as read |
| `DELETE` | `/api/notifications/{id}` | 🔒 Protected | Delete notification |

---

### 📊 Reports (Admin Only)

| Method | Endpoint | Access | Description |
|--------|----------|--------|-------------|
| `GET` | `/api/reports/dashboard` | 🛡️ Admin | Overview: users, scholarships, applications, awards totals |
| `GET` | `/api/reports/applications` | 🛡️ Admin | Application report with status filter |
| `GET` | `/api/reports/awards` | 🛡️ Admin | Award report with total amount granted |

**Dashboard response includes:**
```json
{
  "total_users": 50,
  "total_students": 45,
  "total_scholarships": 8,
  "active_scholarships": 5,
  "total_applications": 120,
  "applications_by_status": {
    "draft": 10,
    "submitted": 30,
    "under_review": 20,
    "approved": 45,
    "rejected": 15
  },
  "total_awards": 45,
  "total_amount_granted": "900000.00"
}
```

---

## 🔑 Authentication

This API uses **Laravel Sanctum** for token-based authentication.

1. Call `POST /api/register` or `POST /api/login`
2. Copy the `token` from the response
3. Add it to every subsequent request header:

```
Authorization: Bearer YOUR_TOKEN_HERE
Accept: application/json
```

Tokens are revoked on `POST /api/logout`.

---

## 👤 Roles & Permissions

| Role | Registration | What they can do |
|------|-------------|-----------------|
| **Admin** | `"role": "Admin"` in register body | Full access to all endpoints including user management, reports, approvals, and granting awards |
| **Student** | Default (`"role": "Student"`) | Apply for scholarships, manage own profile, upload documents, view own applications and notifications |

> Students cannot access other users' data. Any attempt returns `403 Forbidden`.

---

## 🧪 Postman Collection

A ready-to-use Postman collection is included in the repository root: **`postman_collection.json`**

### How to import:
1. Open **Postman**
2. Click **Import** → drag in `postman_collection.json`
3. Set the collection variable `base_url` = `http://localhost:8000`
4. Run **🔐 Authentication → Login** — it **automatically saves your token** to `{{token}}`
5. All 35 requests are pre-configured and ready to test

---

## 🔄 Application Workflow

```
Student registers / logs in
        ↓
Browses active scholarships  GET /api/scholarships
        ↓
Creates an application       POST /api/applications       [status: draft]
        ↓
Uploads documents            POST /api/documents
        ↓
Submits application          POST /api/applications/{id}/submit   [status: submitted]
        ↓
Admin marks for review       POST /api/applications/{id}/review   [status: under_review]
        ↓
Admin approves or rejects    POST /api/applications/{id}/approve  [status: approved]
        ↓
Admin grants award           POST /api/awards
        ↓
Admin sends notification     POST /api/awards/{id}/notify
        ↓
Student receives notification in /api/notifications
```

---

<div align="center">

**Built with ❤️ using Laravel 12**  
*Scholarship Management System — IT Department Project*

</div>
