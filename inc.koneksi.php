<?php
$koneksi = mysqli_connect("localhost", "root", "", "afterhour_db");
if (!$koneksi) {
    die("Koneksi Prosedural Afterhour Gagal: " . mysqli_connect_error());
}
?>