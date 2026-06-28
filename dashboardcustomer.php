<?php
if (!isset($_SESSION)) { session_start(); }

if (!isset($_SESSION["role"]) || $_SESSION["role"] != 'customer') {
    echo "<script>alert('Akses Ditolak! Harap login sebagai member.');</script>";
    echo '<script>window.location="index.php?p=login";</script>'; exit();
}

require_once('inc.koneksi.php');
require_once('class/class.Booking.php');
require_once('class/class.Discount.php');

$objBooking = new Booking();
$objDiscount= new Discount();

$myBookings      = $objBooking->SelectCustomerBookings($_SESSION['userid']);
$activeDiscounts = $objDiscount->SelectActiveDiscounts();
$pendingCount    = count(array_filter($myBookings, fn($b) => $b->payment_status == 'Pending'));
$paidCount       = count(array_filter($myBookings, fn($b) => $b->payment_status == 'Paid'));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Member Dashboard — Afterhour</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Boldonse&display=swap" rel="stylesheet">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <style>
        *{box-sizing:border-box}
        body{background:#070103;font-family:'DM Sans',sans-serif;margin:0;padding:0;color:#EDE8DC}
        .sidebar{position:fixed;top:0;left:0;width:240px;height:100vh;background:#0d0208;border-right:1px solid #2f0618;z-index:100;display:flex;flex-direction:column}
        .sidebar-brand{padding:24px 20px;border-bottom:1px solid #2f0618}
        .sidebar-brand img{height:36px;width:auto;object-fit:contain}
        .sidebar-brand .role-tag{font-size:10px;color:#5aacff;letter-spacing:2px;text-transform:uppercase;margin-top:4px;display:block}
        .sidebar-nav{flex:1;overflow-y:auto;padding:16px 0}
        .nav-section-label{font-size:10px;color:#4a3a42;letter-spacing:2px;text-transform:uppercase;padding:12px 20px 6px;font-weight:600}
        .nav-item{display:flex;align-items:center;gap:12px;padding:11px 20px;color:#8A7E6C;font-size:13px;font-weight:500;text-decoration:none;transition:all 0.2s;border-left:2px solid transparent}
        .nav-item:hover{color:#fff;background:rgba(255,255,255,0.04);text-decoration:none}
        .nav-item.active{color:#8bd100;border-left-color:#8bd100;background:rgba(139,209,0,0.06);font-weight:700}
        .nav-icon{font-size:15px;width:18px;text-align:center;flex-shrink:0}
        .sidebar-footer{padding:16px 20px;border-top:1px solid #2f0618}
        .user-mini{display:flex;align-items:center;gap:10px;margin-bottom:12px}
        .user-avatar{width:34px;height:34px;border-radius:50%;background:#0a1a2a;border:2px solid #1a3a5a;display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:13px;overflow:hidden}
        .user-avatar img{width:100%;height:100%;object-fit:cover}
        .user-name{font-size:13px;font-weight:600;color:#fff}
        .user-role{font-size:10px;color:#8A7E6C;letter-spacing:1px;text-transform:uppercase}
        .btn-logout{display:flex;align-items:center;gap:8px;width:100%;background:#2f0618;border:1px solid #4f1030;color:#e81b7b;font-size:12px;font-weight:700;padding:9px 14px;border-radius:8px;text-decoration:none;transition:all 0.2s;letter-spacing:0.5px;text-transform:uppercase}
        .btn-logout:hover{background:#e81b7b;color:#fff;text-decoration:none}
        .main-content{margin-left:240px;min-height:100vh;background:#070103}
        .topbar{background:#0d0208;border-bottom:1px solid #2f0618;padding:0 32px;height:64px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:50}
        .topbar-title{font-family:'Boldonse',sans-serif;font-size:18px;color:#fff}
        .topbar-breadcrumb{font-size:12px;color:#8A7E6C}
        .page-body{padding:28px 32px}
        .dash-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:18px;margin-bottom:28px}
        .dash-card{background:#1f0410;border:1px solid #2f0618;border-radius:14px;padding:22px;transition:border-color 0.3s}
        .dash-card:hover{border-color:#8bd100}
        .dash-card .dc-icon{font-size:28px;margin-bottom:10px}
        .dash-card .dc-val{font-family:'Boldonse',sans-serif;font-size:32px;color:#fff;line-height:1;margin-bottom:4px}
        .dash-card .dc-label{font-size:11px;color:#8A7E6C;letter-spacing:1px;text-transform:uppercase}
        .btn-book-now{display:inline-flex;align-items:center;gap:8px;background:#8bd100;color:#000;font-weight:700;font-size:13px;padding:12px 28px;border-radius:30px;text-decoration:none;transition:all 0.3s;letter-spacing:0.5px}
        .btn-book-now:hover{background:#fff;transform:translateY(-2px);text-decoration:none}
        .btn-history{display:inline-flex;align-items:center;gap:8px;background:transparent;color:#8A7E6C;font-size:13px;padding:12px 20px;border-radius:30px;text-decoration:none;border:1px solid #2f0618;transition:all 0.3s;margin-left:12px}
        .btn-history:hover{color:#fff;border-color:#555;text-decoration:none}
        .promo-banner{background:linear-gradient(135deg,#0d1a00,#1a3a00);border:1px solid #2a5a00;border-radius:14px;padding:20px 24px;margin-bottom:24px;display:flex;align-items:center;gap:18px}
        .promo-banner .pb-pct{font-family:'Boldonse',sans-serif;font-size:36px;color:#8bd100;line-height:1;flex-shrink:0}
        .promo-banner h5{color:#fff;font-family:'DM Sans',sans-serif;font-weight:700;font-size:16px;margin:0 0 4px}
        .promo-banner p{color:#aaa;font-size:13px;margin:0}
        @media(max-width:991px){.dash-grid{grid-template-columns:1fr}.main-content{margin-left:0}.sidebar{display:none}.page-body{padding:20px 16px}.topbar{padding:0 16px}}
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-brand">
        <img src="assets/logo/Afterhour.png" alt="Afterhour">
        <span class="role-tag">Member Area</span>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section-label">Menu</div>
        <a href="dashboardcustomer.php" class="nav-item <?= (!isset($_GET['p'])) ? 'active' : '' ?>">
            <span class="nav-icon glyphicon glyphicon-home"></span><span>Dashboard</span>
        </a>
        <a href="dashboardcustomer.php?p=booking" class="nav-item <?= (isset($_GET['p']) && $_GET['p']=='booking') ? 'active' : '' ?>">
            <span class="nav-icon glyphicon glyphicon-calendar"></span><span>Booking Meja</span>
        </a>
        <a href="dashboardcustomer.php?p=mybookings" class="nav-item <?= (isset($_GET['p']) && $_GET['p']=='mybookings') ? 'active' : '' ?>">
            <span class="nav-icon glyphicon glyphicon-list-alt"></span><span>Riwayat Booking</span>
        </a>
    </nav>
    <div class="sidebar-footer">
        <div class="user-mini">
            <div class="user-avatar"><?= strtoupper(substr($_SESSION['name'],0,1)) ?></div>
            <div>
                <div class="user-name"><?= htmlspecialchars($_SESSION['name']) ?></div>
                <div class="user-role">Member</div>
            </div>
        </div>
        <a href="logout.php" class="btn-logout">
            <span class="glyphicon glyphicon-log-out"></span><span>Logout</span>
        </a>
    </div>
</div>

<div class="main-content">
    <div class="topbar">
        <div>
            <div class="topbar-title">
                <?php
                $p = isset($_GET['p']) ? $_GET['p'] : 'home';
                $titles = ['booking'=>'Booking Meja','mybookings'=>'Riwayat Booking'];
                echo isset($titles[$p]) ? $titles[$p] : 'Dashboard';
                ?>
            </div>
            <div class="topbar-breadcrumb">Afterhour Member &rsaquo; <?= htmlspecialchars($_SESSION['name']) ?></div>
        </div>
        <div style="font-size:12px;color:#8A7E6C"><?= date('d M Y, H:i') ?> WIB</div>
    </div>

    <div class="page-body">
        <?php
        switch ($p) {
            case 'booking':
                include('pages/booking.php'); break;
            case 'mybookings':
                include('pages/mybookings.php'); break;
            default:
                ?>
                <div style="margin-bottom:24px">
                    <h2 style="font-family:'Boldonse',sans-serif;font-size:22px;color:#fff;margin:0 0 4px">Halo, <?= htmlspecialchars($_SESSION['name']) ?> 🎱</h2>
                    <p style="color:#8A7E6C;font-size:13px;margin:0">Selamat datang di Afterhour Member Area. Siap bermain hari ini?</p>
                </div>

                <!-- Stats -->
                <div class="dash-grid">
                    <div class="dash-card">
                        <div class="dc-icon">📋</div>
                        <div class="dc-val"><?= count($myBookings) ?></div>
                        <div class="dc-label">Total Booking</div>
                    </div>
                    <div class="dash-card">
                        <div class="dc-icon">⏳</div>
                        <div class="dc-val" style="color:#ffaa00"><?= $pendingCount ?></div>
                        <div class="dc-label">Menunggu Konfirmasi</div>
                    </div>
                    <div class="dash-card">
                        <div class="dc-icon">✅</div>
                        <div class="dc-val" style="color:#8bd100"><?= $paidCount ?></div>
                        <div class="dc-label">Sudah Lunas</div>
                    </div>
                </div>

                <!-- Promo aktif -->
                <?php if (!empty($activeDiscounts)): $d = $activeDiscounts[0]; ?>
                <div class="promo-banner">
                    <div class="pb-pct"><?= (int)$d->discount_pct ?>%</div>
                    <div>
                        <h5>🎁 Promo: <?= htmlspecialchars($d->title) ?></h5>
                        <p><?= htmlspecialchars($d->description) ?> &mdash; s/d <?= date('d M Y',strtotime($d->valid_until)) ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <!-- CTA -->
                <div style="background:#1f0410;border:1px solid #2f0618;border-radius:14px;padding:28px;text-align:center;margin-top:8px">
                    <div style="font-size:40px;margin-bottom:12px">🎱</div>
                    <h4 style="color:#fff;font-family:'DM Sans',sans-serif;font-weight:700;font-size:20px;margin:0 0 8px">Reservasi Meja Sekarang</h4>
                    <p style="color:#8A7E6C;font-size:14px;margin:0 0 20px">Pilih meja Regular, VIP Smoking, atau VVIP favoritmu di outlet terdekat.</p>
                    <a href="dashboardcustomer.php?p=booking" class="btn-book-now">
                        <span class="glyphicon glyphicon-calendar"></span> BOOKING MEJA
                    </a>
                    <a href="dashboardcustomer.php?p=mybookings" class="btn-history">
                        <span class="glyphicon glyphicon-list-alt"></span> Riwayat Saya
                    </a>
                </div>
                <?php
                break;
        }
        ?>
    </div>
</div>
</body>
</html>
