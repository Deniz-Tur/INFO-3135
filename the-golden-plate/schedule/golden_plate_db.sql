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
-- ------------------------------------------------------
-- 6. Events (for special days / occasions)
-- ------------------------------------------------------

DROP TABLE IF EXISTS events;
CREATE TABLE events (
    event_id    INT AUTO_INCREMENT PRIMARY KEY,
    title       VARCHAR(100) NOT NULL,
    event_date  DATE NOT NULL,
    start_time  TIME NULL,
    end_time    TIME NULL,
    capacity    INT NOT NULL,
    description TEXT,
    is_public   TINYINT(1) NOT NULL DEFAULT 1,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ------------------------------------------------------
-- 7. Event Reservations (per event, with capacity)
-- ------------------------------------------------------

DROP TABLE IF EXISTS event_reservations;
CREATE TABLE event_reservations (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    event_id    INT NOT NULL,
    user_id     INT NULL,
    guest_name  VARCHAR(100) NULL,
    num_guests  INT NOT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_event_res_event
        FOREIGN KEY (event_id)
        REFERENCES events(event_id)
        ON DELETE CASCADE,

    CONSTRAINT fk_event_res_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE SET NULL
);
