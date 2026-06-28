<?php
class Connection
{
    protected $connection;

    public function __construct()
    {
        $this->connection = mysqli_connect("localhost", "root", "", "afterhour_db");
        if (!$this->connection) {
            die("Koneksi OOP Engine Afterhour Gagal: " . mysqli_connect_error());
        }
        mysqli_set_charset($this->connection, "utf8mb4");
    }
}
?>
