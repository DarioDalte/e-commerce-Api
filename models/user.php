<?php
class User{

    private $conn;
    private $table_name = 'users';

    public $id;
    public $email;
    public $password;

    public function __construct($db){
        $this->conn = $db;
    }

    function read(){
        $query = "SELECT * FROM $this->table_name where email = '$this->email' AND password = '$this->password'";
        $result = $this->conn->query($query);
        return  $result;
    }


}


?>