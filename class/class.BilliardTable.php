<?php
require_once(__DIR__ . '/class.Connection.php');

class BilliardTable extends Connection
{
    private $table_id = '';
    private $outlet_id = '';
    private $table_number = '';
    private $class_type = '';
    private $price_per_hour = '';
    private $status = '';

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

    // Fungsi krusial: Mengambil meja HANYA yang ada di outlet pilihan user (Pola W12)
    public function SelectTablesByOutlet($selected_outlet)
    {
        $sql = "SELECT * FROM billiard_tables WHERE outlet_id = '$selected_outlet' AND status = 'Available'";
        $result = mysqli_query($this->connection, $sql);
        $arrResult = [];
        if (mysqli_num_rows($result) > 0) {
            while ($data = mysqli_fetch_assoc($result)) {
                $obj = new BilliardTable();
                $obj->table_id = $data['table_id'];
                $obj->table_number = $data['table_number'];
                $obj->class_type = $data['class_type'];
                $obj->price_per_hour = $data['price_per_hour'];
                $obj->status = $data['status'];
                $arrResult[] = $obj;
            }
        }
        return $arrResult;
    }
}
?>