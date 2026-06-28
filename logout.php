<?php
if (!isset($_SESSION)) { session_start(); }
$_SESSION = [];
session_destroy();
echo "<script>alert('Anda telah keluar dari sistem Afterhour. Sampai jumpa!');</script>";
echo '<script>window.location="index.php";</script>';
?>
