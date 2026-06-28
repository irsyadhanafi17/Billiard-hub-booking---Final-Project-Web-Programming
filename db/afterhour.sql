-- ============================================================
--  Afterhour Billiard & Lounge — Database Schema
--  Password semua akun demo: password
--  Hash $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi = "password"
--
--  CATATAN: Jika Anda ingin set password plain (misal "12345678"),
--  sistem login otomatis mendeteksi dan tetap bisa login.
--  Lalu otomatis di-upgrade ke bcrypt.
-- ============================================================

CREATE DATABASE IF NOT EXISTS afterhour_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE afterhour_db;

-- ─── USERS ────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS users (
    userid      INT PRIMARY KEY AUTO_INCREMENT,
    email       VARCHAR(100) UNIQUE NOT NULL,
    password    VARCHAR(255) NOT NULL,
    name        VARCHAR(100) NOT NULL,
    role        ENUM('customer','manager','admin') DEFAULT 'customer',
    avatar      VARCHAR(150) NULL,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─── OUTLETS ──────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS outlets (
    outlet_id   INT PRIMARY KEY AUTO_INCREMENT,
    outlet_name VARCHAR(100) NOT NULL,
    location    VARCHAR(200) NOT NULL,
    manager_id  INT NULL,
    FOREIGN KEY (manager_id) REFERENCES users(userid) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─── BILLIARD TABLES ──────────────────────────────────────
CREATE TABLE IF NOT EXISTS billiard_tables (
    table_id        INT PRIMARY KEY AUTO_INCREMENT,
    outlet_id       INT NOT NULL,
    table_number    VARCHAR(10) NOT NULL,
    class_type      ENUM('Regular Floor','VIP Smoking','VVIP') NOT NULL,
    price_per_hour  DECIMAL(10,2) NOT NULL,
    status          ENUM('Available','Booked','Maintenance') DEFAULT 'Available',
    FOREIGN KEY (outlet_id) REFERENCES outlets(outlet_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─── BOOKINGS ─────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS bookings (
    booking_id      INT PRIMARY KEY AUTO_INCREMENT,
    userid          INT NOT NULL,
    table_id        INT NOT NULL,
    booking_date    DATE NOT NULL,
    start_time      TIME NOT NULL,
    duration_hours  INT NOT NULL,
    total_price     DECIMAL(10,2) NOT NULL,
    payment_status  ENUM('Pending','Paid','Cancelled') DEFAULT 'Pending',
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userid)   REFERENCES users(userid),
    FOREIGN KEY (table_id) REFERENCES billiard_tables(table_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─── DISCOUNTS ────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS discounts (
    discount_id     INT PRIMARY KEY AUTO_INCREMENT,
    title           VARCHAR(100) NOT NULL,
    description     TEXT NOT NULL,
    discount_pct    DECIMAL(5,2) NOT NULL,
    valid_from      DATE NOT NULL,
    valid_until     DATE NOT NULL,
    is_active       TINYINT(1) DEFAULT 1,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─── SEED: USERS ──────────────────────────────────────────
-- Hash di bawah = "password" (bcrypt)
INSERT INTO users (email, password, name, role) VALUES
('admin@afterhour.id',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Super Admin',    'admin'),
('manager@afterhour.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Manager Cikini',  'manager'),
('demo@afterhour.id',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Demo Customer',   'customer')
ON DUPLICATE KEY UPDATE
    password = VALUES(password),
    name     = VALUES(name),
    role     = VALUES(role);

-- ─── SEED: OUTLETS ────────────────────────────────────────
INSERT INTO outlets (outlet_name, location, manager_id) VALUES
('Afterhour Cikini',  'RR6Q+82 Cikini, Jakarta Pusat',          2),
('Afterhour Menteng', 'QR8P+4M Menteng Dalam, Jakarta Pusat',   NULL),
('Afterhour Sunter',  'VV55+22 Sunter Agung, Jakarta Utara',    NULL),
('Afterhour PIK',     'VQM4+MH Kapuk Muara PIK, Jakarta Utara', NULL),
('Afterhour Poins',   'PQ6H+2F Lebak Bulus, Jakarta Selatan',   NULL),
('M Billiards Blok M','QR52+5V Melawai, Jakarta Barat',         NULL)
ON DUPLICATE KEY UPDATE outlet_name = VALUES(outlet_name);

-- ─── SEED: BILLIARD TABLES ────────────────────────────────
INSERT INTO billiard_tables (outlet_id, table_number, class_type, price_per_hour) VALUES
(1,'A1','Regular Floor',35000),(1,'A2','Regular Floor',35000),
(1,'B1','VIP Smoking',60000),(1,'B2','VIP Smoking',60000),
(1,'C1','VVIP',100000),
(2,'A1','Regular Floor',35000),(2,'A2','Regular Floor',35000),
(2,'B1','VIP Smoking',60000),(2,'C1','VVIP',100000),
(3,'A1','Regular Floor',35000),(3,'A2','Regular Floor',35000),
(3,'B1','VIP Smoking',60000),
(4,'A1','Regular Floor',35000),
(4,'B1','VIP Smoking',60000),(4,'B2','VIP Smoking',60000),
(4,'C1','VVIP',100000),(4,'C2','VVIP',100000),
(5,'A1','Regular Floor',35000),(5,'A2','Regular Floor',35000),
(5,'B1','VIP Smoking',60000),
(6,'A1','Regular Floor',35000),(6,'A2','Regular Floor',35000),
(6,'B1','VIP Smoking',60000);

-- ─── SEED: DISCOUNTS ──────────────────────────────────────
INSERT INTO discounts (title, description, discount_pct, valid_from, valid_until) VALUES
('Happy Hour Spesial',
 'Diskon 20% untuk sesi bermain weekday pukul 10.00-16.00. Nikmati meja premium dengan harga lebih hemat!',
 20.00, '2026-06-01', '2026-12-31'),
('Weekend Warrior',
 'Diskon 15% setiap akhir pekan. Ajak teman-teman dan rasakan serunya bermain di Afterhour!',
 15.00, '2026-06-01', '2026-12-31'),
('New Member Welcome',
 'Selamat datang! Dapatkan diskon 10% untuk booking pertama Anda sebagai anggota baru Afterhour.',
 10.00, '2026-01-01', '2026-12-31');
