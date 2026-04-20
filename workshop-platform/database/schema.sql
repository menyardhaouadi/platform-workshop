CREATE DATABASE IF NOT EXISTS workshop_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE workshop_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('student','admin') DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE workshops (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    instructor VARCHAR(100),
    date DATE NOT NULL,
    time TIME NOT NULL,
    duration INT DEFAULT 60,
    capacity INT DEFAULT 30,
    location VARCHAR(200),
    image VARCHAR(255),
    status ENUM('active','cancelled','completed') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    workshop_id INT NOT NULL,
    status ENUM('registered','cancelled') DEFAULT 'registered',
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_reg (user_id, workshop_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (workshop_id) REFERENCES workshops(id) ON DELETE CASCADE
);

CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    subject VARCHAR(200) NOT NULL,
    body TEXT NOT NULL,
    reply TEXT,
    replied_at TIMESTAMP NULL,
    is_read TINYINT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE
);


INSERT INTO workshops (title, description, instructor, date, time, duration, capacity, location) VALUES
('Cybersecurity Fundamentals', 'Network security, threat analysis and best practices.', 'Dr. Sarah Ahmed', DATE_ADD(CURDATE(), INTERVAL 7 DAY), '10:00:00', 180, 25, 'Room A101'),
('Web Dev with React', 'Build modern web apps using React.js and hooks.', 'Prof. Karim Mansour', DATE_ADD(CURDATE(), INTERVAL 10 DAY), '14:00:00', 240, 30, 'Lab B205'),
('Machine Learning Basics', 'ML algorithms and hands-on Python projects.', 'Dr. Leila Bouzid', DATE_ADD(CURDATE(), INTERVAL 14 DAY), '09:00:00', 300, 20, 'Room C310'),
('Docker & Kubernetes', 'Container orchestration from basics to production.', 'Eng. Youssef Trabelsi', DATE_ADD(CURDATE(), INTERVAL 21 DAY), '11:00:00', 180, 20, 'Lab B205');
