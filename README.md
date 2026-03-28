# 📚 Scholarship Management System (SMS)
 
A Laravel-based REST API for managing scholarships, student applications, documents, awards, and notifications.
 
---
 
## 🚀 Setup Instructions
 
### 1. Clone the Repository
```bash
git clone https://github.com/baby-dino-ux/laran-sms.git
cd laran-sms
```
 
### 2. Install Dependencies
```bash
composer install
```
 
### 3. Configure Environment
```bash
cp .env.example .env
php artisan key:generate
```
 
Edit `.env` with your database credentials:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laran_sms
DB_USERNAME=root
DB_PASSWORD=
```
 
### 4. Install Laravel Sanctum
```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```
 
### 5. Run Migrations & Seed
```bash
php artisan migrate --seed
```
 
### 6. Link Storage
```bash
php artisan storage:link
```
 
### 7. Serve the Application
```bash
php artisan serve
```
 
API base URL: `http://localhost:8000/api`
 
---
 
## 🔐 Default Accounts (after seeding)
 
| Role    | Email               | Password   |
|---------|---------------------|------------|
| Admin   | admin@sms.com       | password   |
| Student | student@sms.com     | password   |
 
---
 
## 📋 Feature List
 
### 2.1 User Authentication
- `POST /api/register` — Register
- `POST /api/login` — Login
- `POST /api/logout` — Logout *(auth required)*
- `GET  /api/me` — Get authenticated user *(auth required)*
 
### 2.2 User Profile Management
- `GET  /api/profile` — View profile
- `PUT  /api/profile` — Edit profile
- `POST /api/profile/picture` — Upload profile picture
 
### 2.3 Scholarship Management *(admin: full CRUD; student: read)*
- `GET    /api/scholarships` — View all scholarships
- `POST   /api/scholarships` — Add scholarship
- `GET    /api/scholarships/{id}` — View scholarship
- `PUT    /api/scholarships/{id}` — Edit scholarship
- `DELETE /api/scholarships/{id}` — Delete scholarship
- `PUT    /api/scholarships/{id}/eligibility` — Set eligibility criteria
 
### 2.4 Application Management
- `GET  /api/applications` — View applications
- `POST /api/applications` — Apply for scholarship
- `GET  /api/applications/{id}` — View application
- `PUT  /api/applications/{id}` — Edit draft application
- `GET  /api/applications/{id}/status` — View application status
- `POST /api/applications/{id}/submit` — Submit application
- `POST /api/applications/{id}/review` — Admin: mark under review
- `POST /api/applications/{id}/approve` — Admin: approve
- `POST /api/applications/{id}/reject` — Admin: reject
 
### 2.5 Document Upload
- `GET    /api/documents` — View documents
- `POST   /api/documents` — Upload document
- `GET    /api/documents/{id}` — View document info
- `GET    /api/documents/{id}/download` — Download document
- `DELETE /api/documents/{id}` — Delete document
 
### 2.6 Scholarship Awards *(admin only: grant/notify)*
- `GET  /api/awards` — View award list
- `POST /api/awards` — Grant award
- `GET  /api/awards/history` — View award history
- `GET  /api/awards/{id}` — View single award
- `POST /api/awards/{id}/notify` — Send award notification
 
### 2.7 Notifications
- `GET    /api/notifications` — View notifications
- `POST   /api/notifications/send` — Admin: send notification
- `GET    /api/notifications/{id}` — View single notification
- `PATCH  /api/notifications/{id}/read` — Mark as read
- `PATCH  /api/notifications/read-all` — Mark all as read
- `DELETE /api/notifications/{id}` — Delete notification
 
### 2.8 Reports *(admin only)*
- `GET /api/reports/dashboard` — Dashboard statistics
- `GET /api/reports/applications` — Application report
- `GET /api/reports/awards` — Award report
 
### 2.9 Admin: User Management
- `GET    /api/users` — View all users
- `GET    /api/users/{id}` — View user
- `PUT    /api/users/{id}` — Edit user
- `DELETE /api/users/{id}` — Delete user
- `PATCH  /api/users/{id}/role` — Assign role
 
---
 
## 🔑 Authentication
 
This API uses **Laravel Sanctum** (token-based). After login or register, include the token in all requests:
 
```
Authorization: Bearer {your_token_here}
```
 
---
 
## 🛠 Tech Stack
 
- **Framework**: Laravel 10
- **Auth**: Laravel Sanctum
- **Database**: MySQL
- **File Storage**: Laravel Storage (local/public disk)
 
---
 
## 📬 Postman Collection
 
Import the Postman collection to test all endpoints.
*(Link to be added after publishing Postman workspace)*