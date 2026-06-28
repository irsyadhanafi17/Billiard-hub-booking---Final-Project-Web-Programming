<?php
require_once(__DIR__ . '/class.Connection.php');

class Outlet extends Connection
{
    private $outlet_id   = '';
    private $outlet_name = '';
    private $location    = '';
    private $manager_id  = '';

    public $hasil   = false;
    public $message = '';

    public function __get($a)   { if (property_exists($this,$a)) return $this->$a; }
    public function __set($a,$v){ if (property_exists($this,$a)) $this->$a = $v; }

    public function SelectAllOutlet()
    {
        $sql = "SELECT o.*, u.name as manager_name FROM outlets o
                LEFT JOIN users u ON u.userid = o.manager_id
                ORDER BY o.outlet_name ASC";
        $res = mysqli_query($this->connection, $sql);
        $arr = [];
        while ($d = mysqli_fetch_assoc($res)) {
            $o = new Outlet();
            $o->outlet_id   = $d['outlet_id'];
            $o->outlet_name = $d['outlet_name'];
            $o->location    = $d['location'];
            $o->manager_id  = $d['manager_id'];
            $arr[] = $o;
        }
        return $arr;
    }

    public function SelectOutletByManager($manager_id)
    {
        $mid = (int)$manager_id;
        $sql = "SELECT * FROM outlets WHERE manager_id = $mid LIMIT 1";
        $res = mysqli_query($this->connection, $sql);
        if (mysqli_num_rows($res) == 1) {
            $this->hasil = true;
            $d = mysqli_fetch_assoc($res);
            $this->outlet_id   = $d['outlet_id'];
            $this->outlet_name = $d['outlet_name'];
            $this->location    = $d['location'];
        }
    }

    public function AddOutlet()
    {
        $name = mysqli_real_escape_string($this->connection, $this->outlet_name);
        $loc  = mysqli_real_escape_string($this->connection, $this->location);
        $mid  = $this->manager_id ? (int)$this->manager_id : 'NULL';
        $sql  = "INSERT INTO outlets (outlet_name,location,manager_id) VALUES ('$name','$loc',$mid)";
        $this->hasil   = mysqli_query($this->connection,$sql);
        $this->message = $this->hasil ? 'Outlet berhasil ditambahkan!' : 'Gagal menambahkan outlet!';
    }

    public function UpdateOutlet()
    {
        $name = mysqli_real_escape_string($this->connection, $this->outlet_name);
        $loc  = mysqli_real_escape_string($this->connection, $this->location);
        $mid  = $this->manager_id ? (int)$this->manager_id : 'NULL';
        $oid  = (int)$this->outlet_id;
        $sql  = "UPDATE outlets SET outlet_name='$name',location='$loc',manager_id=$mid WHERE outlet_id=$oid";
        $this->hasil   = mysqli_query($this->connection,$sql);
        $this->message = $this->hasil ? 'Outlet diperbarui!' : 'Gagal memperbarui outlet!';
    }

    public function DeleteOutlet()
    {
        $oid = (int)$this->outlet_id;
        $sql = "DELETE FROM outlets WHERE outlet_id=$oid";
        $this->hasil   = mysqli_query($this->connection,$sql);
        $this->message = $this->hasil ? 'Outlet dihapus!' : 'Gagal menghapus outlet!';
    }
}
?>
