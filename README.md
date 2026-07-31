# Task Management System API

A RESTful Task Management API built with Laravel following clean architecture principles and Laravel best practices.

The system allows authenticated users to manage their projects and tasks with proper authorization, validation, API resources, background jobs, and automated testing.

---

## 🚀 Features

### Authentication

* User registration
* User login
* User logout
* API authentication using Laravel Sanctum

### Projects Management

Each user can manage their own projects.

Features:

* Create project
* List user's projects
* View project details
* Update project
* Delete project (Soft Delete)

Project fields:

* Name
* Description
* Status:

  * Active
  * Completed
  * Archived

### Tasks Management

Each project contains multiple tasks.

Features:

* Create task
* List project tasks
* View task
* Update task
* Delete task (Soft Delete)

Task fields:

* Title

* Description

* Priority:

  * Low
  * Medium
  * High

* Status:

  * Todo
  * In Progress
  * Done

* Due Date

Additional capabilities:

* Filter by status
* Filter by priority
* Search by title
* Pagination

---

# 📊 Dashboard

The dashboard endpoint provides project and task statistics for the authenticated user.

Available metrics:

* Total Projects
* Active Projects
* Total Tasks
* Completed Tasks
* Pending Tasks
* Overdue Tasks

---

# ⚙️ Technical Stack

* Laravel
* PHP
* MySQL
* Laravel Sanctum
* Eloquent ORM
* REST API
* PHPUnit / Pest Feature Tests

---

# 🏗️ Architecture

The project follows a clean and maintainable structure:

```
app
├── Http
│   ├── Controllers
│   ├── Requests
│   └── Resources
│
├── Services
│
├── Policies
│
├── Enums
│
├── Jobs
│
└── Notifications
```

Implemented concepts:

* Service Layer
* Form Request Validation
* API Resource Transformation
* Authorization Policies
* Enum-based statuses
* Eloquent Relationships
* Soft Deletes
* Queue Jobs
* Database Notifications

---

# 🔐 Authorization

Users can only access their own data.

Implemented authorization rules:

* Users can manage only their projects.
* Users can access tasks belonging to their own projects.
* Unauthorized access returns proper HTTP responses.

---

# 🔔 Queue & Notifications

The system includes background processing for overdue tasks.

Flow:

```
Scheduler
    ↓
Command
    ↓
Queue Job
    ↓
Database Notification
```

When a task becomes overdue:

* The system detects overdue tasks.
* A queued job is dispatched.
* The user receives a notification.
* Duplicate notifications are prevented.

---

# 🧪 Testing

The project includes automated Feature Tests covering:

* Authentication
* Project CRUD
* Task CRUD
* Authorization
* Filtering
* Searching
* Dashboard functionality
* Queue job dispatching
* Notifications

Current test coverage:

```
21 Tests
83 Assertions
```

Run tests:

```bash
php artisan test
```

---

# 📦 Installation

## Requirements

Make sure you have:

* PHP 8.2+
* Composer
* MySQL
* Laravel requirements installed

---

## Clone Repository

```bash
git clone <repository-url>

cd task-management
```

---

## Install Dependencies

```bash
composer install
```

---

## Environment Setup

Create your environment file:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

---

## Database Configuration

Update your `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_management
DB_USERNAME=root
DB_PASSWORD=
```

---

## Run Migrations

```bash
php artisan migrate
```

---

## Seed Sample Data

```bash
php artisan db:seed
```

Or:

```bash
php artisan migrate:fresh --seed
```

---

## Run Application

```bash
php artisan serve
```

Application will be available at:

```
http://127.0.0.1:8000
```

---

# 📚 API Documentation

Base URL:

```
/api/v1
```

Authentication:

All protected endpoints require:

```
Authorization: Bearer {token}
Accept: application/json
```

---

# API Endpoints

## Authentication

| Method | Endpoint       | Description   |
| ------ | -------------- | ------------- |
| POST   | /auth/register | Register user |
| POST   | /auth/login    | Login user    |
| POST   | /auth/logout   | Logout user   |

---

## Projects

| Method | Endpoint       | Description    |
| ------ | -------------- | -------------- |
| GET    | /projects      | List projects  |
| POST   | /projects      | Create project |
| GET    | /projects/{id} | View project   |
| PUT    | /projects/{id} | Update project |
| DELETE | /projects/{id} | Delete project |

---

## Tasks

| Method | Endpoint                         | Description |
| ------ | -------------------------------- | ----------- |
| GET    | /projects/{project}/tasks        | List tasks  |
| POST   | /projects/{project}/tasks        | Create task |
| GET    | /projects/{project}/tasks/{task} | View task   |
| PUT    | /projects/{project}/tasks/{task} | Update task |
| DELETE | /projects/{project}/tasks/{task} | Delete task |

Filtering example:

```
/projects/1/tasks?status=done
```

```
/projects/1/tasks?priority=high
```

Search example:

```
/projects/1/tasks?search=meeting
```

---

## Dashboard

| Method | Endpoint   | Description     |
| ------ | ---------- | --------------- |
| GET    | /dashboard | User statistics |

---

# 📌 Postman Collection

A complete Postman collection is included containing:

* Authentication requests
* Projects requests
* Tasks requests
* Dashboard request

---

# 👨‍💻 Development Notes

The project was built with focus on:

* Clean code
* Separation of responsibilities
* Maintainable architecture
* Proper API responses
* Secure authorization
* Automated testing

---

# License

This project is for assessment and demonstration purposes.
