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

$query = "SELECT * FROM users WHERE email='$data->email'";
$result = $conn->query($query);
$num = $result->num_rows;

if($num == 0){

    $data->name = ucfirst($data->name);
    $data->surname = ucfirst($data->surname);
    
    // $first_part = "name";
    // $second_part = "'$data->name'";
    
    // foreach ($data as $key => $value) {
    //     if($value != "" && $key != "name"){
    //         $first_part = $first_part .  ", $key";
    //         $second_part = $second_part . ", '$value'";
    
    //     }
    // }
    
    $query = "INSERT INTO users (name, surname, email, password) VALUES ('$data->name', '$data->surname', '$data->email', '$data->password')";
    $conn->query($query);
    $return_data = msg(1, 200, "Registrazione effettuata");
}else{
    $return_data = msg(0, 422, "Indirizzo email già preso!");
}

echo json_encode($return_data);




// print_r($result);






?>