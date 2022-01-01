<?php
require __DIR__.'/../config/JwtHandler.php';
class Auth extends JwtHandler{

    protected $db;
    protected $headers;
    protected $token;
    public function __construct($db,$headers) {
        parent::__construct();
        $this->db = $db;
        $this->headers = $headers;
    }

    public function isAuth(){
        if(array_key_exists('Authorization',$this->headers) && !empty(trim($this->headers['Authorization']))):
            $this->token = explode(" ", trim($this->headers['Authorization']));
            if(isset($this->token[1]) && !empty(trim($this->token[1]))):
                
                $data = $this->jwtDecodeData($this->token[1]);

                if(isset($data['auth']) && isset($data['data']->user_id) && $data['auth']):
                    $user = $this->fetchUser($data['data']->user_id);
                    return $user;

                else:
                    return null;

                endif; // End of isset($this->token[1]) && !empty(trim($this->token[1]))
                
            else:
                return null;

            endif;// End of isset($this->token[1]) && !empty(trim($this->token[1]))

        else:
            return null;

        endif;
    }

    protected function fetchUser($user_id){
        try{
            $query = "SELECT `name`,`email` FROM `users` WHERE `id`=$user_id";

            $result = $this->db->query($query);
            $num = $result->num_rows;
            if($num):
                $data = $result->fetch_object();
                return [
                    'success' => 1,
                    'status' => 200,
                    'user' => $data
                ];
            else:
                return null;
            endif;
        }
        catch(Exception $e){
            return null;
        }
    }
}