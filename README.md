<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo">
</p>

# VU-Platform

A multi-tenant **SaaS HR & Recruitment Management API** built with Laravel. Companies register on the platform and run their full hiring pipeline — from job posting to final hiring decision — with role-based access control and Stripe-powered billing.

---

## Features

- 🏢 **Company registration** with automatic Owner account creation
- 👥 **Team management** — invite members, assign roles, manage access
- 💼 **Job positions** with department categories and custom pipeline stages
- 📋 **Application tracking** — receive, shortlist, accept, or reject candidates
- 🎙️ **Interview scheduling** with interviewer assignment
- ⭐ **Candidate evaluations** with Q&A-based scoring
- 📊 **Role-specific dashboards** with KPIs per role (Owner, Admin, HR, Interviewers...)
- 💳 **Stripe billing** — plans, subscriptions, and webhook support
- 🔔 **Notifications** — email notifications for invites, OTPs, and key events
- 🐳 **Docker-ready** with Nginx, PHP-FPM, and MySQL

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Framework | Laravel 11 |
| Language | PHP 8.3 |
| Auth | JWT (`php-open-source-saver/jwt-auth`) |
| Authorization | Spatie Laravel Permission |
| Payments | Stripe PHP SDK |
| OTP | `ichtrojan/laravel-otp` |
| Database | MySQL |
| Web Server | Nginx |
| Containers | Docker + Docker Compose |

---

## Roles

| Role | Description |
|------|-------------|
| `Owner` | Full access — company owner |
| `Admin` | Manage team, billing, and settings |
| `HR` | Manage positions and applications |
| `HR-Interviewer` | Conduct HR interviews |
| `Tech-Interviewer` | Conduct technical interviews |
| `Account-Manager` | Handle billing and accounts |

---

## Getting Started

### Requirements

- PHP 8.2+
- Composer
- MySQL
- Node.js
- Stripe account

### Installation

```bash
# 1. Clone the repo
git clone https://github.com/your-org/VU-Platform.git
cd VU-Platform

# 2. Install dependencies
composer install
npm install && npm run build

# 3. Setup environment
cp .env.example .env
php artisan key:generate
php artisan jwt:secret

# 4. Edit .env — set DB_*, STRIPE_KEY, STRIPE_SECRET, STRIPE_WEBHOOK_SECRET, MAIL_*

# 5. Run migrations & seeders
php artisan migrate --seed

# 6. Start server
php artisan serve
```

### Docker Quick Start

```bash
cp .env.example .env
# fill in .env values
docker-compose up --build
```

---

## API Overview

**Base URL:** `/api`  
**Auth Header:** `Authorization: Bearer {jwt_token}`

### Authentication

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/register` | Register company + Owner |
| POST | `/login` | Login and receive JWT |
| DELETE | `/logout` | Invalidate token |
| POST | `/forget-Password` | Send OTP for password reset |
| POST | `/check-Otp` | Validate OTP |
| POST | `/reset-password` | Reset password |
| POST | `/email/verifay` | Verify email via OTP |

### Core Resources *(Auth Required)*

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET/POST | `/positions` | List / create job positions |
| GET/POST | `/applications` | List / submit applications |
| POST | `/applications/{id}/{decision}` | Accept, reject, or shortlist |
| GET/POST | `/interviews` | List / schedule interviews |
| GET/POST | `/evaluations` | List / submit evaluations |
| GET/POST | `/categories` | Manage job categories |
| GET/POST | `/position-stages` | Manage pipeline stages |

### Team & Settings *(Auth Required)*

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/team` | List team members |
| POST | `/team/invite` | Invite a new member |
| PATCH | `/team/{user}` | Update member role/status |
| DELETE | `/team/{id}` | Remove member |
| POST | `/set-password` | Activate invited account |
| GET/PUT | `/setting` | View / update company settings |

### Dashboards *(Role-gated)*

| Endpoint | Role |
|----------|------|
| `GET /dashboard/owner` | Owner |
| `GET /dashboard/admin` | Admin |
| `GET /dashboard/hr` | HR |
| `GET /dashboard/hr-interviewer` | HR-Interviewer |
| `GET /dashboard/tech-interviewer` | Tech-Interviewer |
| `GET /dashboard/account-manager` | Account-Manager |

### Billing

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/plans` | List available plans |
| POST | `/payments/create` | Create Stripe SetupIntent |
| POST | `/payments/subscription` | Subscribe to a plan |
| POST | `/stripe/webhook` | Stripe event webhook |

### Notifications

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/notifications` | All notifications (paginated) |
| GET | `/notifications/unread` | Unread only |
| PUT | `/notifications/{id}/read` | Mark as read |
| PUT | `/notifications/read-all` | Mark all as read |
| DELETE | `/notifications/{id}` | Delete notification |

---

## Database Schema (Summary)

```
Company ──< User
Company ──< Position ──< Application ──< Interview
                     └──< Evaluation ──< EvaluationAnswer
Position ──< PositionStage
Position >── Category
Application >── Candidate
Company ──< payments >── Plan ──< plan_features
Company ──< subscriptions >── Plan
```

---

## License

This project is proprietary software. All rights reserved.
