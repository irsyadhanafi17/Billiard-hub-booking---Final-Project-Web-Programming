<?php
require_once(__DIR__ . '/class.Connection.php');

class Discount extends Connection
{
    private $discount_id  = '';
    private $title        = '';
    private $description  = '';
    private $discount_pct = '';
    private $valid_from   = '';
    private $valid_until  = '';
    private $is_active    = 1;

    public $hasil   = false;
    public $message = '';

    public function __get($a)    { if (property_exists($this,$a)) return $this->$a; }
    public function __set($a,$v) { if (property_exists($this,$a)) $this->$a = $v; }

    public function SelectAllDiscounts()
    {
        $sql = "SELECT * FROM discounts ORDER BY created_at DESC";
        $res = mysqli_query($this->connection,$sql);
        $arr = [];
        while ($d = mysqli_fetch_assoc($res)) {
            $o = new Discount();
            $o->discount_id  = $d['discount_id'];
            $o->title        = $d['title'];
            $o->description  = $d['description'];
            $o->discount_pct = $d['discount_pct'];
            $o->valid_from   = $d['valid_from'];
            $o->valid_until  = $d['valid_until'];
            $o->is_active    = $d['is_active'];
            $arr[] = $o;
        }
        return $arr;
    }

    public function SelectActiveDiscounts()
    {
        $today = date('Y-m-d');
        $sql   = "SELECT * FROM discounts WHERE is_active=1 AND valid_from<='$today' AND valid_until>='$today' ORDER BY discount_pct DESC";
        $res   = mysqli_query($this->connection,$sql);
        $arr   = [];
        while ($d = mysqli_fetch_assoc($res)) {
            $o = new Discount();
            $o->discount_id  = $d['discount_id'];
            $o->title        = $d['title'];
            $o->description  = $d['description'];
            $o->discount_pct = $d['discount_pct'];
            $o->valid_from   = $d['valid_from'];
            $o->valid_until  = $d['valid_until'];
            $o->is_active    = $d['is_active'];
            $arr[] = $o;
        }
        return $arr;
    }

    public function AddDiscount()
    {
        $title = mysqli_real_escape_string($this->connection,$this->title);
        $desc  = mysqli_real_escape_string($this->connection,$this->description);
        $pct   = (float)$this->discount_pct;
        $from  = mysqli_real_escape_string($this->connection,$this->valid_from);
        $until = mysqli_real_escape_string($this->connection,$this->valid_until);
        $active= (int)$this->is_active;
        $sql   = "INSERT INTO discounts (title,description,discount_pct,valid_from,valid_until,is_active)
                  VALUES ('$title','$desc',$pct,'$from','$until',$active)";
        $this->hasil   = mysqli_query($this->connection,$sql);
        $this->message = $this->hasil ? 'Diskon berhasil ditambahkan!' : 'Gagal menambahkan diskon!';
        if ($this->hasil) $this->discount_id = mysqli_insert_id($this->connection);
    }

    public function UpdateDiscount()
    {
        $did   = (int)$this->discount_id;
        $title = mysqli_real_escape_string($this->connection,$this->title);
        $desc  = mysqli_real_escape_string($this->connection,$this->description);
        $pct   = (float)$this->discount_pct;
        $from  = mysqli_real_escape_string($this->connection,$this->valid_from);
        $until = mysqli_real_escape_string($this->connection,$this->valid_until);
        $active= (int)$this->is_active;
        $sql   = "UPDATE discounts SET title='$title',description='$desc',discount_pct=$pct,
                  valid_from='$from',valid_until='$until',is_active=$active WHERE discount_id=$did";
        $this->hasil   = mysqli_query($this->connection,$sql);
        $this->message = $this->hasil ? 'Diskon diperbarui!' : 'Gagal memperbarui diskon!';
    }

    public function DeleteDiscount()
    {
        $did = (int)$this->discount_id;
        $sql = "DELETE FROM discounts WHERE discount_id=$did";
        $this->hasil   = mysqli_query($this->connection,$sql);
        $this->message = $this->hasil ? 'Diskon dihapus!' : 'Gagal menghapus diskon!';
    }

    public function SelectOneDiscount($id)
    {
        $did = (int)$id;
        $sql = "SELECT * FROM discounts WHERE discount_id=$did LIMIT 1";
        $res = mysqli_query($this->connection,$sql);
        if (mysqli_num_rows($res)==1) {
            $this->hasil = true;
            $d = mysqli_fetch_assoc($res);
            $this->discount_id  = $d['discount_id'];
            $this->title        = $d['title'];
            $this->description  = $d['description'];
            $this->discount_pct = $d['discount_pct'];
            $this->valid_from   = $d['valid_from'];
            $this->valid_until  = $d['valid_until'];
            $this->is_active    = $d['is_active'];
        }
    }
}
?>
