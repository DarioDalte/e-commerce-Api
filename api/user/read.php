<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../models/user.php';


$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$data = json_decode(file_get_contents("php://input"));


$user->email = $data->email;
$user->password = $data->password;

$result = $user->read();
$data = $result->fetch_object();
$num = $result->num_rows;
if($num == 1){
    $user_data = array('id' => $data->id, 'email' => $data->email);
    echo json_encode($user_data);
}else{
    echo json_encode("");
}

?>