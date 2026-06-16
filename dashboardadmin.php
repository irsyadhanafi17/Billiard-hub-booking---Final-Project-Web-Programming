<?php
// dashboardadmin.php
if (!isset($_SESSION)) {
    session_start();
}

// W13: Proteksi Otorisasi Admin ketat
require_once('pages/authorization_admin.php');
require_once("inc.koneksi.php");
?>
<!DOCTYPE html>
<html>

<head>
    <title>Admin Panel - Afterhour Billiard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>

<body>
    <nav class="navbar navbar-inverse" style="background-color: #2c000e; border-color: #1a0008;">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="dashboardadmin.php" style="color: #ffd700;">AFTERHOUR ADMIN PANEL</a>
            </div>
            <ul class="nav navbar-nav">
                <li><a href="dashboardadmin.php">Home Dashboard</a></li>
                <li><a href="dashboardadmin.php?p=bookinglist">Kelola Transaksi Booking</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="#">Logged in as: <b>
                            <?php echo $_SESSION['name']; ?>
                        </b></a></li>
                <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <?php
        // W8: Dynamic Page Routing khusus Admin
        $page = isset($_GET['p']) ? $_GET['p'] : 'home';
        switch ($page) {
            case 'bookinglist':
                include('pages/bookinglist.php'); // File laporan transaksi & konfirmasi
                break;
            default:
                echo "<h3>Selamat Datang di Ruang Kontrol Admin Afterhour, " . $_SESSION['name'] . "!</h3>";
                echo "<p>Gunakan menu di atas untuk memantau pesanan meja billiard masuk dan memperbarui status pembayaran kasir.</p>";
                break;
        }
        ?>
    </div>
</body>

</html>