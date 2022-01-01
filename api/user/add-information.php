<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../../config/database.php';
if($_SERVER["REQUEST_METHOD"] != "POST"){
    exit();
}
function msg($success,$status,$message,$extra = []){
    return array_merge([
        'success' => $success,
        'status' => $status,
        'message' => $message
    ],$extra);
}

$return_data = [];

$database = new Database();

$conn = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));


$set_part = "";
$flag = true;
foreach ($data as $key => $value) {
    if($value != ""){
        if($flag){
            $set_part = $set_part .  "$key='$value'";
            $flag = false;
        }else{
            $set_part = $set_part .  ", $key='$value'";
        }
        
    }
    
}

$query = "UPDATE users SET $set_part WHERE email='$data->email'";
$conn->query($query);
    
?>