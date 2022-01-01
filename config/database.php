<?php
class Database{
    
    private $db_host = 'localhost';
    private $db_username = 'root';
    private $db_password = '';
    private $db_name = 'e-commerce';


    public function getConnection(){

        $conn = new mysqli($this->db_host, $this->db_username, $this->db_password, $this->db_name);

        if ($conn -> connect_errno) {
            echo "Failed to connect to MySQL: " . $conn -> connect_error;
            exit();
        }else{
            return $conn;
        }
    }
}