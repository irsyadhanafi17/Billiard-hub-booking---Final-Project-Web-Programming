CREATE DATABASE IF NOT EXISTS afterhour_db;
USE afterhour_db;

CREATE TABLE IF NOT EXISTS users (
    userid INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(50) NOT NULL,
    role ENUM('customer', 'manager', 'admin') DEFAULT 'customer',
    avatar VARCHAR(100) NULL 
);

CREATE TABLE IF NOT EXISTS outlets (
    outlet_id INT PRIMARY KEY AUTO_INCREMENT,
    outlet_name VARCHAR(50) NOT NULL,
    location VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS billiard_tables (
    table_id INT PRIMARY KEY AUTO_INCREMENT,
    outlet_id INT,
    table_number VARCHAR(10) NOT NULL,
    class_type ENUM('Regular Floor', 'VIP Smoking', 'VVIP') NOT NULL,
    price_per_hour DECIMAL(10,2) NOT NULL,
    status ENUM('Available', 'Booked', 'Maintenance') DEFAULT 'Available',
    FOREIGN KEY (outlet_id) REFERENCES outlets(outlet_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS bookings (
    booking_id INT PRIMARY KEY AUTO_INCREMENT,
    userid INT,
    table_id INT,
    booking_date DATE NOT NULL,
    start_time TIME NOT NULL,
    duration_hours INT NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    payment_status ENUM('Pending', 'Paid', 'Cancelled') DEFAULT 'Pending',
    FOREIGN KEY (userid) REFERENCES users(userid),
    FOREIGN KEY (table_id) REFERENCES billiard_tables(table_id)
);