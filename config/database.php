<?php
class Database{
    
    //Heroku DB credential
    private $db_host = 'eu-cdbr-west-02.cleardb.net';
    private $db_username = 'b3e1483f49bb1d';
    private $db_password = 'b385a480';
    private $db_name = 'heroku_0db9ecd71309d74';


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