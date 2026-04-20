# platform-workshop
This project was created and developed by:  Helmi Jbili Wajih Nsiri Menyar Dhaouadi Rouea Hammami Ridha Ben Iseia
TechInstitute is a full-stack web application designed to simplify the management of educational workshops. It allows students to explore and register for workshops, communicate with administrators, and provides admins with powerful tools to manage the platform.

🚀 Features
🔐 User Authentication (Register / Login / Logout)
🎓 Browse and register for workshops
❌ Cancel workshop participation
💬 Messaging system (Student ↔ Admin)
🛠️ Admin dashboard (manage workshops, users, messages)
📊 Platform statistics
🏗️ Architecture

The application follows a modular structure inspired by microservices:

Frontend: HTML, CSS, JavaScript
Backend: PHP (separated services)
Database: MySQL
🔧 Services
Auth Service → Authentication (login/register/logout)
Workshop Service → Workshops & registrations
Message Service → Messaging system
Admin Service → Admin management & statistics
📁 Project Structure
frontend/
  index.html
  dashboard.html
  admin.html
  css/style.css
  js/app.js

services/
  auth-service/
  workshop-service/
  message-service/
  admin-service/

database/
  schema.sql
🗄️ Database

Main tables:

users → Stores users and admins
workshops → Workshop details
registrations → User registrations
messages → Communication system
⚙️ Installation & Setup
Import database/schema.sql into MySQL

Configure database credentials in:

services/*/db.php

Run a local PHP server:

php -S localhost:8000

Open in browser:

http://localhost:8000/frontend/index.html
🔑 Admin Access
Email: admin@institute.com
Password: password
👥 Project Team
Helmi Jbili
Wajih Nsiri
Menyar Dhaouadi
Rouea Hammami
Ridha Ben Iseia
📈 Future Improvements
Email notifications
Mobile-friendly version
Role expansion (teachers, moderators)
Real-time updates
🎯 Purpose

This project demonstrates:

Full-stack web development
API structuring and service separation
Database design
Building scalable systems
