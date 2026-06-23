<?php
if (!isset($_SESSION)) {
    session_start();
}
session_destroy();
echo "<script>alert('Anda telah keluar dari sistem Afterhour');</script>";
echo '<script>window.location = "index.php";</script>';
?>