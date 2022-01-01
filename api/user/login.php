<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");



function msg($success,$status,$message,$extra = []){
    return array_merge([
        'success' => $success,
        'status' => $status,
        'message' => $message
    ],$extra);
}

require '../../config/database.php';
require '../../config/JwtHandler.php';

$db_connection = new Database();
$conn = $db_connection->getConnection();

$data = json_decode(file_get_contents("php://input"));
$returnData = [];

// IF REQUEST METHOD IS NOT EQUAL TO POST
if($_SERVER["REQUEST_METHOD"] != "POST"):
    $returnData = msg(0,404,'Page Not Found!');

// CHECKING EMPTY FIELDS
elseif(!isset($data->email) 
    || !isset($data->password)
    || empty(trim($data->email))
    || empty(trim($data->password))
    ):

    $fields = ['fields' => ['email','password']];
    $returnData = msg(0,422,'Please Fill in all Required Fields!',$fields);

// IF THERE ARE NO EMPTY FIELDS THEN-
else:
    $email = trim($data->email);
    $password = trim($data->password);

    // CHECKING THE EMAIL FORMAT (IF INVALID FORMAT)
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)):
        $returnData = msg(0,422,'Indirizzo email non valido!');
    


    // THE USER IS ABLE TO PERFORM THE LOGIN ACTION
    else:
        try{
            
            $query = "SELECT * FROM users WHERE email='$email'";
            $result = $conn->query($query);
          
            $num = $result->num_rows;
            
         

            // IF THE USER IS FOUNDED BY EMAIL
            if($num):
                $data = $result->fetch_object();
                // $check_password = password_verify($password, $row['password']);
                $check_password =  true;

                // VERIFYING THE PASSWORD (IS CORRECT OR NOT?)
                // IF PASSWORD IS CORRECT THEN SEND THE LOGIN TOKEN
                if($check_password):

                    $jwt = new JwtHandler();
                    $token = $jwt->jwtEncodeData(
                        'http://localhost/php-api/',
                        array("user_id"=> $data->id)
                    );
                    
                    $returnData = [
                        'success' => 1,
                        'message' => 'You have successfully logged in.',
                        'token' => $token
                    ];

                // IF INVALID PASSWORD
                else:
                    $returnData = msg(0,422,'Password non valida!');
                endif;

            // IF THE USER IS NOT FOUNDED BY EMAIL THEN SHOW THE FOLLOWING ERROR
            else:
                $returnData = msg(0,422,'Indirizzo email non valido!');
            endif;
        }
        catch(Exception $e){
            $returnData = msg(0,500,$e->getMessage());
        }

    endif;

endif;

echo json_encode($returnData);