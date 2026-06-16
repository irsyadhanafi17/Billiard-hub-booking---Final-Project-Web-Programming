<?php
if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION["role"])) {
    echo "<script>alert('Akses Ditolak! Harap login terlebih dahulu.');</script>";
    echo '<script>window.location="index.php?p=login";</script>';
    exit();
} else {
    if ($_SESSION["role"] != 'admin') {
        echo "<script>alert('Hanya akun Admin yang dapat mengakses halaman manajemen ini!');</script>";
        echo '<script>window.location="index.php";</script>';
        exit();
    }
}
?>