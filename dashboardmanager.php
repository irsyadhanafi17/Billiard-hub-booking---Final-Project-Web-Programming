<?php
if (!isset($_SESSION)) { session_start(); }
require_once('pages/authorization_manager.php');
require_once('inc.koneksi.php');
require_once('class/class.Outlet.php');
require_once('class/class.Booking.php');

$objOutlet = new Outlet();
$objOutlet->SelectOutletByManager($_SESSION['userid']);
$outletName = $objOutlet->hasil ? $objOutlet->outlet_name : 'Belum ada outlet';
$outletId   = $objOutlet->hasil ? $objOutlet->outlet_id  : 0;

$objBooking = new Booking();
$pending = $outletId ? $objBooking->CountByStatus('Pending', $outletId) : 0;
$paid    = $outletId ? $objBooking->CountByStatus('Paid',    $outletId) : 0;
$revenue = $outletId ? $objBooking->SumRevenue($outletId) : 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Manager Panel — Afterhour</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Boldonse&display=swap" rel="stylesheet">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <style>
        *{box-sizing:border-box}
        body{background:#070103;font-family:'DM Sans',sans-serif;margin:0;padding:0;color:#EDE8DC}
        .sidebar{position:fixed;top:0;left:0;width:240px;height:100vh;background:#0d0208;border-right:1px solid #2f0618;z-index:100;display:flex;flex-direction:column}
        .sidebar-brand{padding:24px 20px;border-bottom:1px solid #2f0618}
        .sidebar-brand img{height:36px;width:auto;object-fit:contain}
        .sidebar-brand .role-tag{font-size:10px;color:#ffaa00;letter-spacing:2px;text-transform:uppercase;margin-top:4px;display:block}
        .sidebar-nav{flex:1;overflow-y:auto;padding:16px 0}
        .nav-section-label{font-size:10px;color:#4a3a42;letter-spacing:2px;text-transform:uppercase;padding:12px 20px 6px;font-weight:600}
        .nav-item{display:flex;align-items:center;gap:12px;padding:11px 20px;color:#8A7E6C;font-size:13px;font-weight:500;text-decoration:none;transition:all 0.2s;border-left:2px solid transparent}
        .nav-item:hover{color:#fff;background:rgba(255,255,255,0.04);text-decoration:none}
        .nav-item.active{color:#ffaa00;border-left-color:#ffaa00;background:rgba(255,170,0,0.06);font-weight:700}
        .nav-icon{font-size:15px;width:18px;text-align:center;flex-shrink:0}
        .sidebar-footer{padding:16px 20px;border-top:1px solid #2f0618}
        .user-mini{display:flex;align-items:center;gap:10px;margin-bottom:12px}
        .user-avatar{width:34px;height:34px;border-radius:50%;background:#3a2a00;border:2px solid #5a4000;display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:13px}
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
        .dash-card{background:#1f0410;border:1px solid #2f0618;border-radius:14px;padding:22px}
        .dash-card .dc-icon{font-size:28px;margin-bottom:10px}
        .dash-card .dc-val{font-family:'Boldonse',sans-serif;font-size:32px;color:#fff;line-height:1;margin-bottom:4px}
        .dash-card .dc-label{font-size:11px;color:#8A7E6C;letter-spacing:1px;text-transform:uppercase}
        .outlet-banner{background:linear-gradient(135deg,#1a0a0e,#2f0618);border:1px solid #4f1030;border-radius:14px;padding:24px;margin-bottom:24px;display:flex;align-items:center;gap:20px}
        .outlet-banner .ob-icon{font-size:36px}
        .outlet-banner h4{color:#ffaa00;font-family:'DM Sans',sans-serif;font-weight:700;font-size:18px;margin:0 0 4px}
        .outlet-banner p{color:#8A7E6C;font-size:13px;margin:0}
        @media(max-width:991px){.dash-grid{grid-template-columns:1fr}.main-content{margin-left:0}.sidebar{display:none}.page-body{padding:20px 16px}.topbar{padding:0 16px}}
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-brand">
        <img src="assets/logo/Afterhour.png" alt="Afterhour">
        <span class="role-tag">Manager Panel</span>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section-label">Overview</div>
        <a href="dashboardmanager.php" class="nav-item <?= (!isset($_GET['p'])) ? 'active' : '' ?>">
            <span class="nav-icon glyphicon glyphicon-home"></span><span>Dashboard</span>
        </a>
        <div class="nav-section-label">Outlet Saya</div>
        <a href="dashboardmanager.php?p=bookinglist" class="nav-item <?= (isset($_GET['p']) && $_GET['p']=='bookinglist') ? 'active' : '' ?>">
            <span class="nav-icon glyphicon glyphicon-list-alt"></span><span>Transaksi Booking</span>
        </a>
        <a href="dashboardmanager.php?p=mejalist" class="nav-item <?= (isset($_GET['p']) && $_GET['p']=='mejalist') ? 'active' : '' ?>">
            <span class="nav-icon glyphicon glyphicon-th"></span><span>Meja Outlet</span>
        </a>
    </nav>
    <div class="sidebar-footer">
        <div class="user-mini">
            <div class="user-avatar"><?= strtoupper(substr($_SESSION['name'],0,1)) ?></div>
            <div>
                <div class="user-name"><?= htmlspecialchars($_SESSION['name']) ?></div>
                <div class="user-role">Manager</div>
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
                $titles = ['bookinglist'=>'Transaksi Booking','mejalist'=>'Meja Outlet'];
                echo isset($titles[$p]) ? $titles[$p] : 'Dashboard';
                ?>
            </div>
            <div class="topbar-breadcrumb">Afterhour Manager &rsaquo; <?= htmlspecialchars($outletName) ?></div>
        </div>
        <div style="font-size:12px;color:#8A7E6C"><?= date('d M Y, H:i') ?> WIB</div>
    </div>

    <div class="page-body">
        <?php
        switch ($p) {
            case 'bookinglist':
                include('pages/manager_bookinglist.php'); break;
            case 'mejalist':
                require_once('class/class.BilliardTable.php');
                $objTable = new BilliardTable();
                $tables   = $outletId ? $objTable->SelectAllTablesByOutlet($outletId) : [];
                ?>
                <div style="margin-bottom:20px">
                    <h3 style="font-family:'Boldonse',sans-serif;font-size:20px;color:#fff;margin:0 0 4px">Meja Outlet</h3>
                    <p style="color:#8bd100;font-size:13px;margin:0">📍 <?= htmlspecialchars($outletName) ?></p>
                </div>
                <div style="background:#1f0410;border:1px solid #2f0618;border-radius:14px;overflow:hidden">
                    <table class="table table-hover" style="margin:0">
                        <thead>
                            <tr style="background:#2f0618">
                                <th style="color:#8A7E6C;font-size:11px;letter-spacing:1px;text-transform:uppercase;border:none;padding:12px 14px">No.</th>
                                <th style="color:#8A7E6C;font-size:11px;letter-spacing:1px;text-transform:uppercase;border:none;padding:12px 14px">Nomor Meja</th>
                                <th style="color:#8A7E6C;font-size:11px;letter-spacing:1px;text-transform:uppercase;border:none;padding:12px 14px">Kelas</th>
                                <th style="color:#8A7E6C;font-size:11px;letter-spacing:1px;text-transform:uppercase;border:none;padding:12px 14px">Harga/Jam</th>
                                <th style="color:#8A7E6C;font-size:11px;letter-spacing:1px;text-transform:uppercase;border:none;padding:12px 14px">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no=1; foreach ($tables as $t): ?>
                            <tr>
                                <td style="border-color:#2f0618;color:#555;padding:12px 14px"><?= $no++ ?></td>
                                <td style="border-color:#2f0618;color:#fff;font-weight:600;padding:12px 14px">Meja <?= htmlspecialchars($t->table_number) ?></td>
                                <td style="border-color:#2f0618;padding:12px 14px">
                                    <?php
                                    $cc = $t->class_type=='Regular Floor'?'#5acc8a':($t->class_type=='VIP Smoking'?'#8a8aff':'#ffaa00');
                                    ?>
                                    <span style="color:<?= $cc ?>;font-weight:600;font-size:13px"><?= htmlspecialchars($t->class_type) ?></span>
                                </td>
                                <td style="border-color:#2f0618;color:#8bd100;font-weight:700;padding:12px 14px">Rp <?= number_format($t->price_per_hour,0,',','.') ?></td>
                                <td style="border-color:#2f0618;padding:12px 14px">
                                    <?php
                                    $sc = $t->status=='Available'?'#8bd100':($t->status=='Booked'?'#ffaa00':'#ff6666');
                                    ?>
                                    <span style="color:<?= $sc ?>;font-size:13px">⬤ <?= htmlspecialchars($t->status) ?></span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php
                break;
            default:
                ?>
                <div style="margin-bottom:24px">
                    <h2 style="font-family:'Boldonse',sans-serif;font-size:22px;color:#fff;margin:0 0 4px">Halo, <?= htmlspecialchars($_SESSION['name']) ?> 👋</h2>
                    <p style="color:#8A7E6C;font-size:13px;margin:0">Pantau dan kelola operasional outlet Anda.</p>
                </div>

                <div class="outlet-banner">
                    <div class="ob-icon">🏢</div>
                    <div>
                        <h4><?= htmlspecialchars($outletName) ?></h4>
                        <p>Outlet yang Anda kelola sebagai Manager Afterhour</p>
                    </div>
                </div>

                <div class="dash-grid">
                    <div class="dash-card">
                        <div class="dc-icon">⏳</div>
                        <div class="dc-val" style="color:#ffaa00"><?= $pending ?></div>
                        <div class="dc-label">Booking Pending</div>
                    </div>
                    <div class="dash-card">
                        <div class="dc-icon">✅</div>
                        <div class="dc-val" style="color:#8bd100"><?= $paid ?></div>
                        <div class="dc-label">Booking Lunas</div>
                    </div>
                    <div class="dash-card">
                        <div class="dc-icon">💰</div>
                        <div class="dc-val" style="color:#8bd100;font-size:18px">Rp <?= number_format($revenue,0,',','.') ?></div>
                        <div class="dc-label">Pendapatan Outlet</div>
                    </div>
                </div>
                <?php
                break;
        }
        ?>
    </div>
</div>
</body>
</html>
