-- ------------------------------------------------------
-- 1. Create Database
-- ------------------------------------------------------

CREATE DATABASE IF NOT EXISTS golden_plate_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE golden_plate_db;

-- ------------------------------------------------------
-- 2. Users Table (Login System)
-- ------------------------------------------------------

DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name     VARCHAR(100) NOT NULL,
    email         VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('customer', 'admin') NOT NULL DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ------------------------------------------------------
-- 3. Employees Table (Staff Members)
-- ------------------------------------------------------

DROP TABLE IF EXISTS employees;
CREATE TABLE employees (
    employee_id INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100) NOT NULL,
    department  VARCHAR(100),
    role        VARCHAR(100) NOT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample employees
INSERT INTO employees (name, department, role) VALUES
('John Smith',   'Kitchen',      'Chef'),
('Sarah Johnes',       'Service',      'Waiter'),
('Harry Baker',  'Bar',          'Bartender'),
('David White',     'Management',   'Manager');

-- ------------------------------------------------------
-- 4. Schedule Table (Employee Shifts)
-- ------------------------------------------------------

DROP TABLE IF EXISTS schedules;
CREATE TABLE schedules (
    schedule_id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    shift_date  DATE NOT NULL,
    shift_start TIME NOT NULL,
    shift_end   TIME NOT NULL,
    notes       TEXT,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_schedule_employee
        FOREIGN KEY (employee_id)
        REFERENCES employees(employee_id)
        ON DELETE CASCADE
);

-- Insert sample shifts
INSERT INTO schedules (employee_id, shift_date, shift_start, shift_end, notes) VALUES
(1, '2025-11-26', '10:00:00', '18:00:00', 'Lunch & dinner shift'),
(2, '2025-11-26', '12:00:00', '20:00:00', 'Evening shift'),
(3, '2025-11-27', '16:00:00', '23:00:00', 'Bar night shift'),
(4, '2025-11-27', '09:00:00', '17:00:00', 'Manager on duty');


-- Add tables and reservations tables to golden_plate_db
-- Run this in phpMyAdmin after your existing database setup

USE golden_plate_db;

-- ------------------------------------------------------
-- 5. Tables Table (Restaurant Tables)
-- ------------------------------------------------------
DROP TABLE IF EXISTS ⁠ reservations ⁠;
DROP TABLE IF EXISTS ⁠ tables ⁠;

CREATE TABLE ⁠ tables ⁠ (
    ⁠ id ⁠ INT AUTO_INCREMENT PRIMARY KEY,
    ⁠ table_number ⁠ VARCHAR(10) NOT NULL UNIQUE,
    ⁠ capacity ⁠ INT NOT NULL,
    ⁠ status ⁠ VARCHAR(20) DEFAULT 'available',
    ⁠ created_at ⁠ TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert 10 restaurant tables
INSERT INTO ⁠ tables ⁠ (⁠ table_number ⁠, ⁠ capacity ⁠, ⁠ status ⁠) VALUES
('1', 2, 'available'),
('2', 2, 'available'),
('3', 4, 'available'),
('4', 4, 'available'),
('5', 6, 'available'),
('6', 6, 'available'),
('7', 8, 'available'),
('8', 2, 'available'),
('9', 4, 'available'),
('10', 6, 'available');

-- Add tables and reservations tables to golden_plate_db
-- Run this in phpMyAdmin after your existing database setup

USE golden_plate_db;

-- ------------------------------------------------------
-- 5. Tables Table (Restaurant Tables)
-- ------------------------------------------------------
DROP TABLE IF EXISTS ⁠ reservations ⁠;
DROP TABLE IF EXISTS ⁠ tables ⁠;

CREATE TABLE ⁠ tables ⁠ (
    ⁠ id ⁠ INT AUTO_INCREMENT PRIMARY KEY,
    ⁠ table_number ⁠ VARCHAR(10) NOT NULL UNIQUE,
    ⁠ capacity ⁠ INT NOT NULL,
    ⁠ status ⁠ VARCHAR(20) DEFAULT 'available',
    ⁠ created_at ⁠ TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert 10 restaurant tables
INSERT INTO ⁠ tables ⁠ (⁠ table_number ⁠, ⁠ capacity ⁠, ⁠ status ⁠) VALUES
('1', 2, 'available'),
('2', 2, 'available'),
('3', 4, 'available'),
('4', 4, 'available'),
('5', 6, 'available'),
('6', 6, 'available'),
('7', 8, 'available'),
('8', 2, 'available'),
('9', 4, 'available'),
('10', 6, 'available');

-- ------------------------------------------------------
-- 6. Reservations Table (Table Bookings)
-- ------------------------------------------------------
CREATE TABLE ⁠ reservations ⁠ (
    ⁠ id ⁠ INT AUTO_INCREMENT PRIMARY KEY,
    ⁠ user_id ⁠ INT NOT NULL,
    ⁠ table_id ⁠ INT NOT NULL,
    ⁠ reservation_date ⁠ DATE NOT NULL,
    ⁠ reservation_time ⁠ VARCHAR(20) NOT NULL,
    ⁠ party_size ⁠ INT NOT NULL,
    ⁠ special_requests ⁠ TEXT,
    ⁠ status ⁠ VARCHAR(20) DEFAULT 'confirmed',
    ⁠ created_at ⁠ TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ⁠ updated_at ⁠ TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;