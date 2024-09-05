<?php

class DatabaseService
{

    private $db_host = "localhost";
    private $db_name = "adb";
    private $db_user = "root";
    private $db_password = "";
    public $conn;

    public function getConnection()
    {

        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->db_host . ";dbname=" . $this->db_name, $this->db_user, $this->db_password);
        } catch (PDOException $exception) {
            echo "Connection failed: " . $exception->getMessage();
        }

        return $this->conn;
    }


    function exec_query($array, $where = "", $debug = false)
    {
        // $databaseService = new DatabaseService();
        $conn = $this->getConnection();

        $fields_name = "";
        $field_value = "";
        foreach ($array as $key => $val) {
            foreach ($val as $field => $value) {

                if (trim($where) != "") {
                    if ($fields_name == "") {
                        $field_value = clear_input(trim($value));
                        $fields_name = trim($field) . " = '" . $field_value . "'";
                    } else {
                        $field_value = clear_input(trim($value));
                        $fields_name .= ", " . trim($field) . " = '" . $field_value . "'";
                    }
                } else {
                    if (trim($fields_name) == "") {
                        $fields_name = trim($field);
                        $field_value = "'" . clear_input(trim($value)) . "'";
                    } else {
                        $fields_name .= "," . trim($field);
                        $field_value .= ",'" . clear_input(trim($value)) . "'";
                    }
                }
            }
            $table_name = trim($key);
        }

        if ($where == "")
            $query = "Insert into " . $table_name . " (" . $fields_name . ") values(" . $field_value . ")";
        else
            $query = "update " . $table_name . " set " . $fields_name . " where " . $where;

        if ($debug == true) {
            echo "<span><font color=green>" . $query . "</font></span>";
        } else {
            // mysqli_query($connection_string,$query);
            $conn->query($query);
        }
        // if($where == "")
        // 	return $mysqli->insert_id;
    }
}
