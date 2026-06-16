<?php
require_once(__DIR__ . '/class.Connection.php');

class Booking extends Connection
{
    private $booking_id, $userid, $table_id, $booking_date, $start_time, $duration_hours, $total_price, $payment_status;
    // Atribut bantuan untuk menampung hasil query SQL INNER JOIN (Pola W12)
    private $customer_name, $outlet_name, $table_number, $class_type;

    public $hasil = false;
    public $message = '';

    public function __get($attribute)
    {
        if (property_exists($this, $attribute))
            return $this->$attribute;
    }

    public function __set($attribute, $value)
    {
        if (property_exists($this, $attribute))
            $this->$attribute = $value;
    }

    public function AddBooking()
    {
        // Ambil harga asli meja per jam dari database untuk proteksi manipulasi harga di sisi client
        $queryPrice = "SELECT price_per_hour FROM billiard_tables WHERE table_id = '$this->table_id'";
        $res = mysqli_query($this->connection, $queryPrice);
        $tableData = mysqli_fetch_assoc($res);

        // Hitung total harga final di sisi server
        $this->total_price = $tableData['price_per_hour'] * $this->duration_hours;

        $sql = "INSERT INTO bookings (userid, table_id, booking_date, start_time, duration_hours, total_price)
                VALUES ('$this->userid', '$this->table_id', '$this->booking_date', '$this->start_time', '$this->duration_hours', '$this->total_price')";
        $this->hasil = mysqli_query($this->connection, $sql);
        $this->message = $this->hasil ? 'Reservasi meja Afterhour berhasil diproses!' : 'Reservasi gagal!';
    }

    public function SelectAllBookings()
    {
        // Pola W12: Wajib INNER JOIN berlapis untuk menarik string deskriptif ke tabel list view
        $sql = "SELECT b.*, u.name as customer_name, o.outlet_name, t.table_number, t.class_type
                FROM bookings b
                INNER JOIN users u ON b.userid = u.userid
                INNER JOIN billiard_tables t ON b.table_id = t.table_id
                INNER JOIN outlets o ON t.outlet_id = o.outlet_id
                ORDER BY b.booking_date DESC, b.start_time DESC";
        $result = mysqli_query($this->connection, $sql);
        $arrResult = [];
        if (mysqli_num_rows($result) > 0) {
            while ($data = mysqli_fetch_assoc($result)) {
                $obj = new Booking();
                $obj->booking_id = $data['booking_id'];
                $obj->customer_name = $data['customer_name'];
                $obj->outlet_name = $data['outlet_name'];
                $obj->table_number = $data['table_number'];
                $obj->class_type = $data['class_type'];
                $obj->booking_date = $data['booking_date'];
                $obj->start_time = $data['start_time'];
                $obj->duration_hours = $data['duration_hours'];
                $obj->total_price = $data['total_price'];
                $obj->payment_status = $data['payment_status'];
                $arrResult[] = $obj;
            }
        }
        return $arrResult;
    }

    public function SelectCustomerBookings($userid)
    {
        // Pola W12: INNER JOIN untuk menarik nama outlet dan nomor meja berdasarkan ID User spesifik
        $sql = "SELECT b.*, o.outlet_name, t.table_number, t.class_type
            FROM bookings b
            INNER JOIN billiard_tables t ON b.table_id = t.table_id
            INNER JOIN outlets o ON t.outlet_id = o.outlet_id
            WHERE b.userid = '$userid'
            ORDER BY b.booking_date DESC, b.start_time DESC";
        $result = mysqli_query($this->connection, $sql);
        $arrResult = [];
        if (mysqli_num_rows($result) > 0) {
            while ($data = mysqli_fetch_assoc($result)) {
                $obj = new Booking();
                $obj->booking_id = $data['booking_id'];
                $obj->outlet_name = $data['outlet_name'];
                $obj->table_number = $data['table_number'];
                $obj->class_type = $data['class_type'];
                $obj->booking_date = $data['booking_date'];
                $obj->start_time = $data['start_time'];
                $obj->duration_hours = $data['duration_hours'];
                $obj->total_price = $data['total_price'];
                $obj->payment_status = $data['payment_status'];
                $arrResult[] = $obj;
            }
        }
        return $arrResult;
    }

    public function ApproveBooking($id)
    {
        $sql = "UPDATE bookings SET payment_status = 'Paid' WHERE booking_id = '$id'";
        $this->hasil = mysqli_query($this->connection, $sql);
        if ($this->hasil) {
            $this->message = 'Pembayaran berhasil dikonfirmasi (LUNAS)!';
        } else {
            $this->message = 'Gagal mengonfirmasi pembayaran!';
        }
    }
}
?>