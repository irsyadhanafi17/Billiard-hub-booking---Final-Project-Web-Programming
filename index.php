<?php
// index.php
if (!isset($_SESSION)) {
    session_start(); // Menyalakan Session (W13)
}

// Menyisipkan koneksi global prosedural permintaan Bu Jati
require_once("inc.koneksi.php");

// Validasi Proteksi: Jika user SUDAH login, langsung tendang ke rumahnya masing-masing
if (isset($_SESSION["role"])) {
    if ($_SESSION["role"] == 'customer') {
        echo '<script>window.location = "dashboardcustomer.php";</script>';
    } else if ($_SESSION["role"] == 'admin') {
        echo '<script>window.location = "dashboardadmin.php";</script>';
    }
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Afterhour Billiard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>

<body>
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">AFTERHOUR</a>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="index.php?p=register">Register</a></li>
                <li><a href="index.php?p=login">Login</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <?php
        // Dinamis Page Selector (W8)
        $page = isset($_GET['p']) ? $_GET['p'] : 'home';
        switch ($page) {
            case 'register':
                include('pages/register.php');
                break;
            case 'login':
                include('pages/login.php');
                break;
            default:
                echo "<h3>Welcome to Afterhour</h3><p>Silakan Login untuk booking meja billiard.</p>";
                break;
        }
        ?>
    </div>
</body>

</html>