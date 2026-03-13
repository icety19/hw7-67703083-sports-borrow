-- Create Database
CREATE DATABASE IF NOT EXISTS sports_borrow;
USE sports_borrow;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Equipment Table
CREATE TABLE IF NOT EXISTS equipment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category VARCHAR(50),
    total_quantity INT DEFAULT 1,
    available_quantity INT DEFAULT 1,
    status ENUM('available', 'maintenance', 'retired') DEFAULT 'available',
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Borrowing Records Table
CREATE TABLE IF NOT EXISTS borrows (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    equipment_id INT,
    borrow_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    return_date TIMESTAMP NULL,
    status ENUM('borrowed', 'returned', 'overdue') DEFAULT 'borrowed',
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (equipment_id) REFERENCES equipment(id)
);

-- Initial Data
INSERT INTO users (username, password, full_name, role) VALUES 
('admin', 'admin123', 'System Administrator', 'admin'),
('user1', 'user123', 'John Doe', 'user');

INSERT INTO equipment (name, category, total_quantity, available_quantity) VALUES 
('Basketball', 'Ball', 5, 5),
('Football', 'Ball', 3, 3),
('Badminton Racket', 'Racket', 10, 10),
('Volleyball', 'Ball', 4, 4);
