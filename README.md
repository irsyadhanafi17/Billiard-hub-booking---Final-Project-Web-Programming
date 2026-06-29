# 🎱 Afterhour Billiard & Lounge — Booking System

Sistem reservasi meja billiard berbasis PHP untuk **Afterhour Billiard & Lounge**, mencakup tiga role pengguna: **Admin**, **Manager**, dan **Customer**.

---

## 👤 Demo Accounts

| Role     | Email                   | Password  |
|----------|-------------------------|-----------|
| Admin    | admin@afterhour.id      | password  |
| Manager  | manager@afterhour.id    | password  |
| Customer | demo@afterhour.id       | password  |

---

## 🏗️ Struktur Project

```
AFTERHOUR/
├── assets/
│   ├── logo/
│   │   └── Afterhour.png                  ← Logo aplikasi
│   └── stocks/
│       ├── Afterhour (1).png
│       ├── Afterhour (2).png
│       ├── Afterhour (3).png
│       ├── Afterhour (4).png
│       ├── Afterhour (5).png
│       ├── Afterhour (6).png
│       ├── Afterhour (7).png
│       ├── Afterhour (8).png
│       ├── Afterhour (9).png
│       ├── Afterhour (10).png
│       ├── Afterhour (11).png
│       └── Afterhour (12).png             ← Foto outlet
│
├── class/
│   ├── .htaccess
│   ├── class.Connection.php               ← Koneksi database (OOP)
│   ├── class.User.php                     ← CRUD user, login, register
│   ├── class.Outlet.php                   ← CRUD outlet
│   ├── class.BilliardTable.php            ← CRUD meja billiard
│   ├── class.Booking.php                  ← CRUD booking & riwayat booking
│   ├── class.Discount.php                 ← CRUD promo/diskon
│   └── class.Mail.php                     ← Email menggunakan PHPMailer
│
├── db/
│   └── afterhour.sql                      ← Database beserta data awal
│
├── pages/
│   ├── authorization_admin.php            ← Middleware Admin
│   ├── authorization_manager.php          ← Middleware Manager
│   ├── login.php                          ← Form Login
│   ├── register.php                       ← Form Registrasi
│   ├── booking.php                        ← Form Booking Customer
│   ├── mybookings.php                     ← Riwayat Booking Customer
│   ├── bookinglist.php                    ← Daftar Booking (Admin)
│   ├── manager_bookinglist.php            ← Daftar Booking (Manager)
│   ├── mejalist.php                       ← CRUD Meja Billiard
│   ├── outletlist.php                     ← CRUD Outlet
│   ├── discountlist.php                   ← CRUD Promo & Broadcast Email
│   └── userlist.php                       ← CRUD Data User
│
├── uploads/
│   ├── 1782639171_6a40ea433396e.jpg
│   └── 1782639511_6a40eb97d6cb1.jpg        ← Upload foto profil user
│
├── vendor/                               ← Library Composer (PHPMailer)
│
├── .gitignore
├── composer.json
├── composer.lock
│
├── dashboardadmin.php                    ← Dashboard Admin
├── dashboardmanager.php                  ← Dashboard Manager
├── dashboardcustomer.php                 ← Dashboard Customer
│
├── index.php                             ← Landing Page
├── logout.php                            ← Logout Session
├── inc.koneksi.php                       ← Koneksi database (Prosedural)
├── update_passwords.php                  ← Utility update password
└── README.md                             ← Dokumentasi project
```

---

## ⚙️ Instalasi

### 1. Database
```sql
-- Import file SQL ke phpMyAdmin atau jalankan via CLI:
mysql -u root -p < db/afterhour.sql
```

### 2. Email (Opsional — PHPMailer)
```bash
# Di folder project:
composer require phpmailer/phpmailer
```
Lalu edit `class/class.Mail.php` — ubah 4 konstanta:
```php
const SMTP_USERNAME  = 'youremail@gmail.com';
const SMTP_PASSWORD  = 'your_app_password';   // Google App Password
const SMTP_FROM      = 'youremail@gmail.com';
const SMTP_FROM_NAME = 'Afterhour Billiard & Lounge';
```
> Tanpa PHPMailer, sistem tetap berfungsi normal — fitur email otomatis fallback ke PHP `mail()`.

### 3. Foto Aset
Salin semua foto ke:
```
assets/stocks/Afterhour (1).png  →  Afterhour (12).png
assets/logo/Afterhour.png
```

### 4. Upload Foto Profil
Pastikan folder `uploads/` ada dan writable:
```bash
mkdir uploads && chmod 755 uploads
```

---

## 🎯 Fitur

### 🔴 Admin
- Dashboard ringkasan (total booking, pendapatan, user)
- Kelola seluruh booking (approve lunas / batalkan)
- CRUD meja billiard (per outlet, ubah status/harga)
- CRUD outlet + assign manager
- CRUD promo/diskon + **broadcast email ke semua customer**
- Lihat daftar semua user

### 🟡 Manager
- Dashboard performa outlet sendiri
- Konfirmasi pembayaran booking di outlet-nya
- Lihat daftar meja outlet-nya

### 🔵 Customer
- Landing page dengan hero slider, galeri foto, outlet, promo
- Booking meja (pilih outlet → meja → jadwal → durasi, preview harga realtime)
- Riwayat booking + status pembayaran
- Lihat promo aktif
- **Email konfirmasi booking otomatis**
- **Email welcome saat registrasi**

---

## 📧 Email Automation

| Trigger           | Email                       | Penerima    |
|-------------------|-----------------------------|-------------|
| Registrasi baru   | Welcome & info akun         | Customer    |
| Booking berhasil  | Konfirmasi + detail booking | Customer    |
| Promo baru dibuat | Broadcast promo/diskon      | Semua member|
| Broadcast manual  | Re-send promo pilihan       | Semua member|

---

## 🗃️ Database

| Tabel           | Fungsi                          |
|-----------------|---------------------------------|
| `users`         | Data pengguna (admin/manager/customer) |
| `outlets`       | Cabang/lokasi Afterhour         |
| `billiard_tables` | Meja per outlet + kelas + harga |
| `bookings`      | Transaksi reservasi             |
| `discounts`     | Promo & diskon aktif            |

---

## 📋 Komponen Penilaian UAS

| No | Komponen                     | Implementasi |
|----|------------------------------|--------------|
| 1  | Desain Interface (10)        | Landing page premium, sidebar dashboard, dark theme |
| 2  | Database (10)                | 5 tabel relasional + seed data |
| 3  | Kelengkapan Fitur (30)       | Booking, CRUD outlet/meja, email, promo broadcast |
| 4  | Fitur dapat dieksekusi (30)  | Auth 3 role, booking flow, approve, cancel |
| 5  | Struktur Project & Code (20) | OOP class-based, separation pages/class/assets |
