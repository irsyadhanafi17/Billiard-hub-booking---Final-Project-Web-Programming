<?php
require_once(__DIR__ . '/class.Connection.php');

class Booking extends Connection
{
    private $booking_id, $userid, $table_id, $booking_date, $start_time,
            $duration_hours, $total_price, $payment_status;
    private $customer_name, $outlet_name, $table_number, $class_type;

    public $hasil   = false;
    public $message = '';

    public function __get($a)   { if (property_exists($this,$a)) return $this->$a; }
    public function __set($a,$v){ if (property_exists($this,$a)) $this->$a = $v; }

    public function AddBooking()
    {
        $uid   = (int)$this->userid;
        $tid   = (int)$this->table_id;
        $date  = mysqli_real_escape_string($this->connection,$this->booking_date);
        $time  = mysqli_real_escape_string($this->connection,$this->start_time);
        $dur   = (int)$this->duration_hours;

        $qp  = "SELECT price_per_hour FROM billiard_tables WHERE table_id=$tid";
        $res = mysqli_query($this->connection,$qp);
        $row = mysqli_fetch_assoc($res);
        $this->total_price = $row['price_per_hour'] * $dur;
        $price = (float)$this->total_price;

        $sql = "INSERT INTO bookings (userid,table_id,booking_date,start_time,duration_hours,total_price)
                VALUES ($uid,$tid,'$date','$time',$dur,$price)";
        $this->hasil   = mysqli_query($this->connection,$sql);
        $this->message = $this->hasil ? 'Reservasi meja Afterhour berhasil diproses!' : 'Reservasi gagal!';
        if ($this->hasil) $this->booking_id = mysqli_insert_id($this->connection);
    }

    public function SelectAllBookings()
    {
        $sql = "SELECT b.*, u.name as customer_name, o.outlet_name, t.table_number, t.class_type
                FROM bookings b
                INNER JOIN users u ON b.userid=u.userid
                INNER JOIN billiard_tables t ON b.table_id=t.table_id
                INNER JOIN outlets o ON t.outlet_id=o.outlet_id
                ORDER BY b.booking_date DESC, b.start_time DESC";
        return $this->_hydrateAll(mysqli_query($this->connection,$sql));
    }

    public function SelectBookingsByOutlet($outlet_id)
    {
        $oid = (int)$outlet_id;
        $sql = "SELECT b.*, u.name as customer_name, o.outlet_name, t.table_number, t.class_type
                FROM bookings b
                INNER JOIN users u ON b.userid=u.userid
                INNER JOIN billiard_tables t ON b.table_id=t.table_id
                INNER JOIN outlets o ON t.outlet_id=o.outlet_id
                WHERE o.outlet_id=$oid
                ORDER BY b.booking_date DESC, b.start_time DESC";
        return $this->_hydrateAll(mysqli_query($this->connection,$sql));
    }

    public function SelectCustomerBookings($userid)
    {
        $uid = (int)$userid;
        $sql = "SELECT b.*, o.outlet_name, t.table_number, t.class_type
                FROM bookings b
                INNER JOIN billiard_tables t ON b.table_id=t.table_id
                INNER JOIN outlets o ON t.outlet_id=o.outlet_id
                WHERE b.userid=$uid
                ORDER BY b.booking_date DESC, b.start_time DESC";
        $res = mysqli_query($this->connection,$sql);
        $arr = [];
        while ($d = mysqli_fetch_assoc($res)) {
            $o = new Booking();
            $o->booking_id     = $d['booking_id'];
            $o->outlet_name    = $d['outlet_name'];
            $o->table_number   = $d['table_number'];
            $o->class_type     = $d['class_type'];
            $o->booking_date   = $d['booking_date'];
            $o->start_time     = $d['start_time'];
            $o->duration_hours = $d['duration_hours'];
            $o->total_price    = $d['total_price'];
            $o->payment_status = $d['payment_status'];
            $arr[] = $o;
        }
        return $arr;
    }

    public function ApproveBooking($id)
    {
        $bid = (int)$id;
        $sql = "UPDATE bookings SET payment_status='Paid' WHERE booking_id=$bid";
        $this->hasil   = mysqli_query($this->connection,$sql);
        $this->message = $this->hasil ? 'Pembayaran berhasil dikonfirmasi (LUNAS)!' : 'Gagal mengonfirmasi pembayaran!';
    }

    public function CancelBooking($id)
    {
        $bid = (int)$id;
        $sql = "UPDATE bookings SET payment_status='Cancelled' WHERE booking_id=$bid";
        $this->hasil   = mysqli_query($this->connection,$sql);
        $this->message = $this->hasil ? 'Booking dibatalkan.' : 'Gagal membatalkan booking!';
    }

    public function CountByStatus($status, $outlet_id=null)
    {
        $s   = mysqli_real_escape_string($this->connection,$status);
        $sql = "SELECT COUNT(*) as cnt FROM bookings b";
        if ($outlet_id) {
            $oid  = (int)$outlet_id;
            $sql .= " INNER JOIN billiard_tables t ON t.table_id=b.table_id WHERE b.payment_status='$s' AND t.outlet_id=$oid";
        } else {
            $sql .= " WHERE b.payment_status='$s'";
        }
        $res = mysqli_query($this->connection,$sql);
        $row = mysqli_fetch_assoc($res);
        return (int)$row['cnt'];
    }

    public function SumRevenue($outlet_id=null)
    {
        $sql = "SELECT IFNULL(SUM(total_price),0) as rev FROM bookings b";
        if ($outlet_id) {
            $oid  = (int)$outlet_id;
            $sql .= " INNER JOIN billiard_tables t ON t.table_id=b.table_id WHERE b.payment_status='Paid' AND t.outlet_id=$oid";
        } else {
            $sql .= " WHERE b.payment_status='Paid'";
        }
        $res = mysqli_query($this->connection,$sql);
        $row = mysqli_fetch_assoc($res);
        return (float)$row['rev'];
    }

    private function _hydrateAll($res)
    {
        $arr = [];
        while ($d = mysqli_fetch_assoc($res)) {
            $o = new Booking();
            $o->booking_id     = $d['booking_id'];
            $o->customer_name  = $d['customer_name'];
            $o->outlet_name    = $d['outlet_name'];
            $o->table_number   = $d['table_number'];
            $o->class_type     = $d['class_type'];
            $o->booking_date   = $d['booking_date'];
            $o->start_time     = $d['start_time'];
            $o->duration_hours = $d['duration_hours'];
            $o->total_price    = $d['total_price'];
            $o->payment_status = $d['payment_status'];
            $arr[] = $o;
        }
        return $arr;
    }
}
?>
