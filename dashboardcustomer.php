<?php
if (!isset($_SESSION)) {
    session_start();
}

// Proteksi Otorisasi Customer (W13)
if (!isset($_SESSION["role"]) || $_SESSION["role"] != 'customer') {
    echo "<script>alert('Akses Ditolak! Harap login sebagai member.');</script>";
    echo '<script>window.location="index.php?p=login";</script>';
    exit();
}

require_once("inc.koneksi.php");
?>
<!DOCTYPE html>
<html>

<head>
    <title>Member Dashboard - Afterhour</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>

<body>
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header"><a class="navbar-brand" href="dashboardcustomer.php">AFTERHOUR MEMBER</a></div>
            <ul class="nav navbar-nav">
                <li><a href="dashboardcustomer.php">Dashboard</a></li>
                <li><a href="dashboardcustomer.php?p=booking">Book a Table</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="#">Halo,
                        <?php echo $_SESSION['name']; ?> (
                        <?php echo $_SESSION['role']; ?>)
                    </a></li>
                <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <?php
        // Routing internal khusus halaman customer (W8)
        $page = isset($_GET['p']) ? $_GET['p'] : 'home';
        switch ($page) {
            case 'booking':
                include('pages/booking.php');
                break;
            case 'mybookings':
                include('pages/mybookings.php'); // File baru yang akan kita buat di bawah
                break;
            default:
                echo "<h3>Selamat Datang, " . $_SESSION['name'] . "!</h3>";
                echo "<p>Gunakan menu di atas untuk melakukan reservasi meja billiard di cabang Afterhour.</p>";
                break;
        }
        ?>
    </div>
</body>

</html>