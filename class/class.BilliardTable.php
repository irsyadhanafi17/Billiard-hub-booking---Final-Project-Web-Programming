<?php
require_once(__DIR__ . '/class.Connection.php');

class BilliardTable extends Connection
{
    private $table_id      = '';
    private $outlet_id     = '';
    private $table_number  = '';
    private $class_type    = '';
    private $price_per_hour= '';
    private $status        = 'Available';

    public $hasil   = false;
    public $message = '';

    public function __get($a)   { if (property_exists($this,$a)) return $this->$a; }
    public function __set($a,$v){ if (property_exists($this,$a)) $this->$a = $v; }

    public function SelectTablesByOutlet($outlet_id)
    {
        $oid = (int)$outlet_id;
        $sql = "SELECT * FROM billiard_tables WHERE outlet_id=$oid AND status='Available' ORDER BY table_number ASC";
        $res = mysqli_query($this->connection,$sql);
        $arr = [];
        while ($d = mysqli_fetch_assoc($res)) {
            $o = new BilliardTable();
            $o->table_id       = $d['table_id'];
            $o->table_number   = $d['table_number'];
            $o->class_type     = $d['class_type'];
            $o->price_per_hour = $d['price_per_hour'];
            $o->status         = $d['status'];
            $arr[] = $o;
        }
        return $arr;
    }

    public function SelectAllTablesByOutlet($outlet_id)
    {
        $oid = (int)$outlet_id;
        $sql = "SELECT * FROM billiard_tables WHERE outlet_id=$oid ORDER BY table_number ASC";
        $res = mysqli_query($this->connection,$sql);
        $arr = [];
        while ($d = mysqli_fetch_assoc($res)) {
            $o = new BilliardTable();
            $o->table_id       = $d['table_id'];
            $o->outlet_id      = $d['outlet_id'];
            $o->table_number   = $d['table_number'];
            $o->class_type     = $d['class_type'];
            $o->price_per_hour = $d['price_per_hour'];
            $o->status         = $d['status'];
            $arr[] = $o;
        }
        return $arr;
    }

    public function SelectAllTables()
    {
        $sql = "SELECT bt.*, o.outlet_name FROM billiard_tables bt
                INNER JOIN outlets o ON o.outlet_id = bt.outlet_id
                ORDER BY o.outlet_name, bt.table_number ASC";
        $res = mysqli_query($this->connection,$sql);
        $arr = [];
        while ($d = mysqli_fetch_assoc($res)) {
            $o = new BilliardTable();
            $o->table_id       = $d['table_id'];
            $o->outlet_id      = $d['outlet_id'];
            $o->table_number   = $d['table_number'];
            $o->class_type     = $d['class_type'];
            $o->price_per_hour = $d['price_per_hour'];
            $o->status         = $d['status'];
            $arr[] = $o;
        }
        return $arr;
    }

    public function AddTable()
    {
        $oid    = (int)$this->outlet_id;
        $num    = mysqli_real_escape_string($this->connection,$this->table_number);
        $class  = mysqli_real_escape_string($this->connection,$this->class_type);
        $price  = (float)$this->price_per_hour;
        $status = mysqli_real_escape_string($this->connection,$this->status);
        $sql    = "INSERT INTO billiard_tables (outlet_id,table_number,class_type,price_per_hour,status)
                   VALUES ($oid,'$num','$class',$price,'$status')";
        $this->hasil   = mysqli_query($this->connection,$sql);
        $this->message = $this->hasil ? 'Meja berhasil ditambahkan!' : 'Gagal menambahkan meja!';
    }

    public function UpdateTable()
    {
        $tid    = (int)$this->table_id;
        $class  = mysqli_real_escape_string($this->connection,$this->class_type);
        $price  = (float)$this->price_per_hour;
        $status = mysqli_real_escape_string($this->connection,$this->status);
        $sql    = "UPDATE billiard_tables SET class_type='$class',price_per_hour=$price,status='$status' WHERE table_id=$tid";
        $this->hasil   = mysqli_query($this->connection,$sql);
        $this->message = $this->hasil ? 'Meja diperbarui!' : 'Gagal memperbarui meja!';
    }

    public function DeleteTable()
    {
        $tid = (int)$this->table_id;
        $sql = "DELETE FROM billiard_tables WHERE table_id=$tid";
        $this->hasil   = mysqli_query($this->connection,$sql);
        $this->message = $this->hasil ? 'Meja dihapus!' : 'Gagal menghapus meja!';
    }
}
?>
