<?php
if (!isset($_SESSION)) { session_start(); }

if (!isset($_SESSION["role"])) {
    echo "<script>alert('Akses Ditolak! Harap login terlebih dahulu.');</script>";
    echo '<script>window.location="index.php?p=login";</script>';
    exit();
} elseif (!in_array($_SESSION["role"], ['admin','manager'])) {
    echo "<script>alert('Hanya akun Manager atau Admin yang dapat mengakses halaman ini!');</script>";
    echo '<script>window.location="index.php";</script>';
    exit();
}
?>
