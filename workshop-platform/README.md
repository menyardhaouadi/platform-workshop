# TechInstitute — Workshop Platform

## Setup

1. Import `database/schema.sql` into MySQL (creates DB + tables + seed data).
2. Open `services/*/db.php` and set your MySQL credentials if needed (default: root / no password).
3. Serve the project from a PHP web server (XAMPP, WAMP, or `php -S localhost:8000`).
4. Open `http://localhost:8000/frontend/index.html` in your browser.

## Admin Login
- Email: admin@institute.com
- Password: password

## Architecture

```
frontend/
  index.html       → Login / Register page
  dashboard.html   → Student dashboard
  admin.html       → Admin panel
  css/style.css    → All styles
  js/app.js        → API Gateway (all fetch calls go through here)

services/
  auth-service/
    db.php         → DB connection
    register.php   → POST: name, email, password
    login.php      → POST: email, password
    logout.php     → POST: (destroys session)

  workshop-service/
    db.php
    get.php            → POST: { user_id } → returns all active workshops
    register.php       → POST: { user_id, workshop_id }
    cancel.php         → POST: { user_id, workshop_id }
    my-workshops.php   → POST: { user_id }

  message-service/
    db.php
    send.php           → POST: { sender_id, subject, body }
    my-messages.php    → POST: { user_id }

  admin-service/
    db.php
    get-workshops.php   → POST: {} → all workshops + student count
    save-workshop.php   → POST: workshop data (create or update by id)
    delete-workshop.php → POST: { id }
    get-messages.php    → POST: {} → all messages with sender info
    reply-message.php   → POST: { id, reply }
    stats.php           → POST: {} → platform stats
    get-users.php       → POST: {} → all registered users

database/
  schema.sql   → Full DB schema + seed data
```

## SQL Tables

| Table         | Purpose                                  |
|---------------|------------------------------------------|
| users         | Stores students and admin accounts       |
| workshops     | Workshop details (title, date, capacity) |
| registrations | Links users to workshops they joined     |
| messages      | Student-to-admin messages and replies    |
