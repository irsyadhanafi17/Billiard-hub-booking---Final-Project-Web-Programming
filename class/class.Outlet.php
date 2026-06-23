<?php
require_once(__DIR__ . '/class.Connection.php');

class Outlet extends Connection
{
    private $outlet_id = '';
    private $outlet_name = '';
    private $location = '';

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

    public function SelectAllOutlet()
    {
        $sql = "SELECT * FROM outlets ORDER BY outlet_name ASC";
        $result = mysqli_query($this->connection, $sql);
        $arrResult = [];
        if (mysqli_num_rows($result) > 0) {
            while ($data = mysqli_fetch_assoc($result)) {
                $obj = new Outlet();
                $obj->outlet_id = $data['outlet_id'];
                $obj->outlet_name = $data['outlet_name'];
                $obj->location = $data['location'];
                $arrResult[] = $obj;
            }
        }
        return $arrResult;
    }
}
?>