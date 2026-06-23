<?php
require_once(__DIR__ . '/class.Connection.php');

class Meja extends Connection
{
    private $id_meja = '';
    private $nomor_meja = '';
    private $kapasitas = '';
    private $lokasi = '';
    private $status = '';

    public $hasil = false;
    public $message = '';

    public function __get($attribute)
    {
        if (property_exists($this, $attribute)) {
            return $this->$attribute;
        }
    }

    public function __set($attribute, $value)
    {
        if (property_exists($this, $attribute)) {
            $this->$attribute = $value;
        }
    }

    public function AddMeja()
    {
        $sql = "INSERT INTO meja (nomor_meja, kapasitas, lokasi, status)
                VALUES ('$this->nomor_meja', '$this->kapasitas', '$this->lokasi', '$this->status')";
        $this->hasil = mysqli_query($this->connection, $sql);

        if ($this->hasil) {
            $this->message = 'Data meja berhasil ditambahkan!';
        } else {
            $this->message = 'Data meja gagal ditambahkan!';
        }
    }

    public function SelectAllMeja()
    {
        $sql = "SELECT * FROM meja ORDER BY nomor_meja ASC";
        $result = mysqli_query($this->connection, $sql);
        $arrResult = array();
        $count = 0;

        if (mysqli_num_rows($result) > 0) {
            while ($data = mysqli_fetch_array($result)) {
                $objMeja = new Meja();
                $objMeja->id_meja = $data['id_meja'];
                $objMeja->nomor_meja = $data['nomor_meja'];
                $objMeja->kapasitas = $data['kapasitas'];
                $objMeja->lokasi = $data['lokasi'];
                $objMeja->status = $data['status'];

                $arrResult[$count] = $objMeja;
                $count++;
            }
        }
        return $arrResult;
    }

    public function SelectOneMeja()
    {
        $sql = "SELECT * FROM meja WHERE id_meja = '$this->id_meja'";
        $resultOne = mysqli_query($this->connection, $sql);

        if (mysqli_num_rows($resultOne) == 1) {
            $this->hasil = true;
            $data = mysqli_fetch_assoc($resultOne);
            $this->nomor_meja = $data['nomor_meja'];
            $this->kapasitas = $data['kapasitas'];
            $this->lokasi = $data['lokasi'];
            $this->status = $data['status'];
        }
    }

public function UpdateMeja()
    {
        $sql = "UPDATE meja
                SET nomor_meja = '$this->nomor_meja',
                    kapasitas = '$this->kapasitas',
                    lokasi = '$this->lokasi',
                    status = '$this->status'
                WHERE id_meja = '$this->id_meja'";
        $this->hasil = mysqli_query($this->connection, $sql);

        if ($this->hasil) {
            $this->message = 'Data meja berhasil diubah!';
        } else {
            $this->message = 'Data meja gagal diubah!';
        }
    }

    public function DeleteMeja()
    {
        $sql = "DELETE FROM meja WHERE id_meja = '$this->id_meja'";
        $this->hasil = mysqli_query($this->connection, $sql);

        if ($this->hasil) {
            $this->message = 'Data meja berhasil dihapus!';
        } else {
            $this->message = 'Data meja gagal dihapus!';
        }
    }
}
?>