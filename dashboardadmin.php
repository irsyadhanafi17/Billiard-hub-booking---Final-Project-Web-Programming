<?php
if (!isset($_SESSION)) { session_start(); }
require_once('pages/authorization_admin.php');
require_once('inc.koneksi.php');
require_once('class/class.Booking.php');
require_once('class/class.User.php');

$objBooking = new Booking();
$objUser    = new User();
$totalPending = $objBooking->CountByStatus('Pending');
$totalPaid    = $objBooking->CountByStatus('Paid');
$totalRevenue = $objBooking->SumRevenue();
$totalUsers   = count($objUser->SelectAllUsers());
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin Panel — Afterhour</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Boldonse&display=swap" rel="stylesheet">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <style>
        *{box-sizing:border-box}
        body{background:#070103;font-family:'DM Sans',sans-serif;margin:0;padding:0;color:#EDE8DC}
        
        .sidebar{position:fixed;top:0;left:0;width:240px;height:100vh;background:#0d0208;border-right:1px solid #2f0618;z-index:100;display:flex;flex-direction:column;transition:width 0.3s}
        .sidebar-brand{padding:24px 20px;border-bottom:1px solid #2f0618}
        .sidebar-brand img{height:36px;width:auto;object-fit:contain}
        .sidebar-brand .role-tag{font-size:10px;color:#e81b7b;letter-spacing:2px;text-transform:uppercase;margin-top:4px;display:block}
        .sidebar-nav{flex:1;overflow-y:auto;padding:16px 0}
        .nav-section-label{font-size:10px;color:#4a3a42;letter-spacing:2px;text-transform:uppercase;padding:12px 20px 6px;font-weight:600}
        .nav-item{display:flex;align-items:center;gap:12px;padding:11px 20px;color:#8A7E6C;font-size:13px;font-weight:500;text-decoration:none;transition:all 0.2s;border-left:2px solid transparent;cursor:pointer}
        .nav-item:hover{color:#fff;background:rgba(255,255,255,0.04);text-decoration:none}
        .nav-item.active{color:#8bd100;border-left-color:#8bd100;background:rgba(139,209,0,0.06);font-weight:700}
        .nav-item .nav-icon{font-size:15px;width:18px;text-align:center;flex-shrink:0}
        .sidebar-footer{padding:16px 20px;border-top:1px solid #2f0618}
        .user-mini{display:flex;align-items:center;gap:10px;margin-bottom:12px}
        .user-avatar{width:34px;height:34px;border-radius:50%;background:#2f0618;border:2px solid #4f1030;display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:13px;flex-shrink:0}
        .user-name{font-size:13px;font-weight:600;color:#fff;line-height:1.3}
        .user-role{font-size:10px;color:#8A7E6C;letter-spacing:1px;text-transform:uppercase}
        .btn-logout{display:flex;align-items:center;gap:8px;width:100%;background:#2f0618;border:1px solid #4f1030;color:#e81b7b;font-size:12px;font-weight:700;padding:9px 14px;border-radius:8px;text-decoration:none;transition:all 0.2s;letter-spacing:0.5px;text-transform:uppercase}
        .btn-logout:hover{background:#e81b7b;color:#fff;text-decoration:none}
        
        .main-content{margin-left:240px;min-height:100vh;background:#070103}
        .topbar{background:#0d0208;border-bottom:1px solid #2f0618;padding:0 32px;height:64px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:50}
        .topbar-title{font-family:'Boldonse',sans-serif;font-size:18px;color:#fff;letter-spacing:0.04em}
        .topbar-breadcrumb{font-size:12px;color:#8A7E6C}
        .page-body{padding:28px 32px}
        
        .dash-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:18px;margin-bottom:28px}
        .dash-card{background:#1f0410;border:1px solid #2f0618;border-radius:14px;padding:22px;transition:border-color 0.3s}
        .dash-card:hover{border-color:#8bd100}
        .dash-card .dc-icon{font-size:28px;margin-bottom:10px}
        .dash-card .dc-val{font-family:'Boldonse',sans-serif;font-size:32px;color:#fff;line-height:1;margin-bottom:4px}
        .dash-card .dc-label{font-size:11px;color:#8A7E6C;letter-spacing:1px;text-transform:uppercase}
        .dash-card .dc-val.green{color:#8bd100}
        .dash-card .dc-val.yellow{color:#ffaa00}
        .dash-card .dc-val.blue{color:#5aacff}
        
        .quick-actions{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:14px;margin-bottom:28px}
        .qa-btn{background:#1f0410;border:1px solid #2f0618;border-radius:12px;padding:20px;text-align:center;text-decoration:none;transition:all 0.25s;cursor:pointer;display:block}
        .qa-btn:hover{border-color:#8bd100;background:#0d1a00;text-decoration:none;transform:translateY(-2px)}
        .qa-btn .qa-icon{font-size:28px;margin-bottom:10px}
        .qa-btn .qa-label{font-size:13px;font-weight:700;color:#fff}
        .qa-btn .qa-desc{font-size:11px;color:#8A7E6C;margin-top:4px}
        @media(max-width:991px){.dash-grid{grid-template-columns:repeat(2,1fr)}.sidebar{width:60px}.sidebar-brand img{display:none}.sidebar-brand .role-tag,.nav-item span:not(.nav-icon),.nav-section-label,.sidebar-footer .user-mini,.sidebar-footer .btn-logout span{display:none}.nav-item{padding:14px;justify-content:center}.main-content{margin-left:60px}.page-body{padding:20px 16px}.topbar{padding:0 16px}}
    </style>
</head>
<body>

<!-- ─── SIDEBAR ─────────────────────────────────────────── -->
<div class="sidebar">
    <div class="sidebar-brand">
        <img src="assets/logo/Afterhour.png" alt="Afterhour">
        <span class="role-tag">Admin Panel</span>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section-label">Overview</div>
        <a href="dashboardadmin.php" class="nav-item <?= (!isset($_GET['p']) || $_GET['p']=='home') ? 'active' : '' ?>">
            <span class="nav-icon glyphicon glyphicon-home"></span><span>Dashboard</span>
        </a>

        <div class="nav-section-label">Manajemen</div>
        <a href="dashboardadmin.php?p=bookinglist" class="nav-item <?= (isset($_GET['p']) && $_GET['p']=='bookinglist') ? 'active' : '' ?>">
            <span class="nav-icon glyphicon glyphicon-list-alt"></span><span>Kelola Booking</span>
        </a>
        <a href="dashboardadmin.php?p=mejalist" class="nav-item <?= (isset($_GET['p']) && $_GET['p']=='mejalist') ? 'active' : '' ?>">
            <span class="nav-icon glyphicon glyphicon-th"></span><span>Kelola Meja</span>
        </a>
        <a href="dashboardadmin.php?p=outletlist" class="nav-item <?= (isset($_GET['p']) && $_GET['p']=='outletlist') ? 'active' : '' ?>">
            <span class="nav-icon glyphicon glyphicon-map-marker"></span><span>Kelola Outlet</span>
        </a>

        <div class="nav-section-label">Marketing</div>
        <a href="dashboardadmin.php?p=discountlist" class="nav-item <?= (isset($_GET['p']) && $_GET['p']=='discountlist') ? 'active' : '' ?>">
            <span class="nav-icon glyphicon glyphicon-tag"></span><span>Promo &amp; Diskon</span>
        </a>

        <div class="nav-section-label">Data</div>
        <a href="dashboardadmin.php?p=userlist" class="nav-item <?= (isset($_GET['p']) && $_GET['p']=='userlist') ? 'active' : '' ?>">
            <span class="nav-icon glyphicon glyphicon-user"></span><span>Data User</span>
        </a>
    </nav>
    <div class="sidebar-footer">
        <div class="user-mini">
            <div class="user-avatar"><?= strtoupper(substr($_SESSION['name'],0,1)) ?></div>
            <div>
                <div class="user-name"><?= htmlspecialchars($_SESSION['name']) ?></div>
                <div class="user-role">Administrator</div>
            </div>
        </div>
        <a href="logout.php" class="btn-logout">
            <span class="glyphicon glyphicon-log-out"></span><span>Logout</span>
        </a>
    </div>
</div>

<!-- ─── MAIN ─────────────────────────────────────────────── -->
<div class="main-content">
    <div class="topbar">
        <div>
            <div class="topbar-title">
                <?php
                $titles = ['bookinglist'=>'Kelola Booking','mejalist'=>'Kelola Meja','outletlist'=>'Kelola Outlet','discountlist'=>'Promo & Diskon','userlist'=>'Data User'];
                $p = isset($_GET['p']) ? $_GET['p'] : 'home';
                echo isset($titles[$p]) ? $titles[$p] : 'Dashboard';
                ?>
            </div>
            <div class="topbar-breadcrumb">Afterhour Admin &rsaquo; <?= isset($titles[$p]) ? $titles[$p] : 'Overview' ?></div>
        </div>
        <div style="font-size:12px;color:#8A7E6C"><?= date('d M Y, H:i') ?> WIB</div>
    </div>

    <div class="page-body">
        <?php
        switch ($p) {
            case 'bookinglist':
                include('pages/bookinglist.php'); break;
            case 'mejalist':
                include('pages/mejalist.php'); break;
            case 'outletlist':
                include('pages/outletlist.php'); break;
            case 'discountlist':
                include('pages/discountlist.php'); break;
            case 'userlist':
                include('pages/userlist.php'); break;
            default:
                // ── HOME DASHBOARD ────────────────────────────────
                ?>
                <div style="margin-bottom:24px">
                    <h2 style="font-family:'Boldonse',sans-serif;font-size:22px;color:#fff;margin:0 0 4px">Selamat Datang, <?= htmlspecialchars($_SESSION['name']) ?> 👋</h2>
                    <p style="color:#8A7E6C;font-size:13px;margin:0">Pantau performa dan kelola seluruh operasional Afterhour dari sini.</p>
                </div>

                <!-- Stats -->
                <div class="dash-grid">
                    <div class="dash-card">
                        <div class="dc-icon">⏳</div>
                        <div class="dc-val yellow"><?= $totalPending ?></div>
                        <div class="dc-label">Booking Pending</div>
                    </div>
                    <div class="dash-card">
                        <div class="dc-icon">✅</div>
                        <div class="dc-val green"><?= $totalPaid ?></div>
                        <div class="dc-label">Booking Lunas</div>
                    </div>
                    <div class="dash-card">
                        <div class="dc-icon">💰</div>
                        <div class="dc-val green" style="font-size:20px">Rp <?= number_format($totalRevenue,0,',','.') ?></div>
                        <div class="dc-label">Total Pendapatan</div>
                    </div>
                    <div class="dash-card">
                        <div class="dc-icon">👥</div>
                        <div class="dc-val blue"><?= $totalUsers ?></div>
                        <div class="dc-label">Total User</div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <h4 style="color:#fff;font-family:'DM Sans',sans-serif;font-weight:700;font-size:15px;margin:0 0 16px">Akses Cepat</h4>
                <div class="quick-actions">
                    <a href="dashboardadmin.php?p=bookinglist" class="qa-btn">
                        <div class="qa-icon">📋</div>
                        <div class="qa-label">Kelola Booking</div>
                        <div class="qa-desc">Konfirmasi pembayaran</div>
                    </a>
                    <a href="dashboardadmin.php?p=mejalist" class="qa-btn">
                        <div class="qa-icon">🎱</div>
                        <div class="qa-label">Kelola Meja</div>
                        <div class="qa-desc">Tambah / ubah meja</div>
                    </a>
                    <a href="dashboardadmin.php?p=outletlist" class="qa-btn">
                        <div class="qa-icon">🏢</div>
                        <div class="qa-label">Kelola Outlet</div>
                        <div class="qa-desc">Manajemen cabang</div>
                    </a>
                    <a href="dashboardadmin.php?p=discountlist" class="qa-btn">
                        <div class="qa-icon">🎁</div>
                        <div class="qa-label">Buat Promo</div>
                        <div class="qa-desc">Broadcast ke member</div>
                    </a>
                    <a href="dashboardadmin.php?p=userlist" class="qa-btn">
                        <div class="qa-icon">👥</div>
                        <div class="qa-label">Data User</div>
                        <div class="qa-desc">Lihat semua member</div>
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
