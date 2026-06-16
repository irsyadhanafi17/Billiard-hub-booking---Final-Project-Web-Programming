<?php
require_once(__DIR__ . '/class.Connection.php');

class User extends Connection
{
    private $userid = 0;
    private $email = '';
    private $password = '';
    private $name = '';
    private $role = '';
    private $avatar = '';
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

    public function AddUser()
    {
        $sql = "INSERT INTO users (email, password, name, role, avatar) 
                VALUES ('$this->email', '$this->password', '$this->name', '$this->role', '$this->avatar')";
        $this->hasil = mysqli_query($this->connection, $sql);
        $this->message = $this->hasil ? 'Registrasi akun Afterhour berhasil!' : 'Registrasi gagal!';
    }

    public function ValidateEmail($inputEmail)
    {
        $sql = "SELECT * FROM users WHERE email = '$inputEmail'";
        $result = mysqli_query($this->connection, $sql);
        if (mysqli_num_rows($result) == 1) {
            $this->hasil = true;
            $data = mysqli_fetch_assoc($result);
            $this->userid = $data['userid'];
            $this->password = $data['password'];
            $this->name = $data['name'];
            $this->email = $data['email'];
            $this->role = $data['role'];
            $this->avatar = $data['avatar'];
        }
    }
}
?>