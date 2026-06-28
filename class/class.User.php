<?php
require_once(__DIR__ . '/class.Connection.php');

class User extends Connection
{
    private $userid   = 0;
    private $email    = '';
    private $password = '';
    private $name     = '';
    private $role     = '';
    private $avatar   = '';

    public $hasil   = false;
    public $message = '';

    public function __get($attr)  { if (property_exists($this,$attr)) return $this->$attr; }
    public function __set($attr,$val) { if (property_exists($this,$attr)) $this->$attr = $val; }

    public function AddUser()
    {
        $email    = mysqli_real_escape_string($this->connection, $this->email);
        $password = mysqli_real_escape_string($this->connection, $this->password);
        $name     = mysqli_real_escape_string($this->connection, $this->name);
        $role     = mysqli_real_escape_string($this->connection, $this->role);
        $avatar   = mysqli_real_escape_string($this->connection, $this->avatar);

        $sql = "INSERT INTO users (email,password,name,role,avatar)
                VALUES ('$email','$password','$name','$role','$avatar')";
        $this->hasil   = mysqli_query($this->connection, $sql);
        $this->message = $this->hasil ? 'Registrasi akun Afterhour berhasil!' : 'Registrasi gagal!';
    }

    public function ValidateEmail($inputEmail)
    {
        $email = mysqli_real_escape_string($this->connection, $inputEmail);
        $sql   = "SELECT * FROM users WHERE email='$email'";
        $res   = mysqli_query($this->connection, $sql);
        if (mysqli_num_rows($res) == 1) {
            $this->hasil  = true;
            $data         = mysqli_fetch_assoc($res);
            $this->userid   = $data['userid'];
            $this->password = $data['password'];
            $this->name     = $data['name'];
            $this->email    = $data['email'];
            $this->role     = $data['role'];
            $this->avatar   = $data['avatar'];
        }
    }

    /* ── Admin: semua user ─────────────────────────────── */
    public function SelectAllUsers()
    {
        $sql  = "SELECT * FROM users ORDER BY created_at DESC";
        $res  = mysqli_query($this->connection, $sql);
        $arr  = [];
        while ($d = mysqli_fetch_assoc($res)) {
            $o = new User();
            $o->userid = $d['userid'];
            $o->email  = $d['email'];
            $o->name   = $d['name'];
            $o->role   = $d['role'];
            $arr[] = $o;
        }
        return $arr;
    }

    /* ── Manager: list customer per outlet ─────────────── */
    public function SelectCustomersByOutlet($outlet_id)
    {
        $oid = (int)$outlet_id;
        $sql = "SELECT DISTINCT u.* FROM users u
                INNER JOIN bookings b ON b.userid = u.userid
                INNER JOIN billiard_tables t ON t.table_id = b.table_id
                WHERE t.outlet_id = $oid AND u.role='customer'
                ORDER BY u.name ASC";
        $res = mysqli_query($this->connection, $sql);
        $arr = [];
        while ($d = mysqli_fetch_assoc($res)) {
            $o = new User();
            $o->userid = $d['userid'];
            $o->email  = $d['email'];
            $o->name   = $d['name'];
            $arr[] = $o;
        }
        return $arr;
    }

    /* ── Semua customer (untuk broadcast email) ─────────── */
    public function SelectAllCustomers()
    {
        $sql = "SELECT * FROM users WHERE role='customer' ORDER BY name ASC";
        $res = mysqli_query($this->connection, $sql);
        $arr = [];
        while ($d = mysqli_fetch_assoc($res)) {
            $o = new User();
            $o->userid = $d['userid'];
            $o->email  = $d['email'];
            $o->name   = $d['name'];
            $arr[] = $o;
        }
        return $arr;
    }

    /* ── Auto-upgrade plain password ke bcrypt setelah login ── */
    public function UpgradePassword($hashedPassword)
    {
        $uid  = (int)$this->userid;
        $hash = mysqli_real_escape_string($this->connection, $hashedPassword);
        mysqli_query($this->connection, "UPDATE users SET password='$hash' WHERE userid=$uid");
    }

    /* ── Cek email sudah dipakai atau belum (return bool) ── */
    public function CheckEmailExists($inputEmail)
    {
        $email = mysqli_real_escape_string($this->connection, $inputEmail);
        $res   = mysqli_query($this->connection, "SELECT userid FROM users WHERE email='$email'");
        return (mysqli_num_rows($res) > 0);
    }
}
?>
