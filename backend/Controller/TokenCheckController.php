<?php
include_once(__DIR__ . "/BaseController.php");
include_once(__DIR__ . "/../inc/config.php");

class TokenCheckController extends BaseController {

    private string $JWT;

    public function CheckGivenToken($data){
        if (empty($data))
        {
            $response = json_encode(array("error" => EMPTY_JSON));
            $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));

        }
        else {
            $data = json_decode($data);
            $this->JWT = $data->JWT;

            include __DIR__ . "/../Model/DAO/SessionDAO.php";
            $DAO = new SessionDAO();
            $user_data=$DAO->fetch_session($this->JWT);

            if (is_array($user_data)){
                return true;
            } else {
                $response = json_encode(array("error" => TOKEN_INVALID));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 401 Authentication Error'));
                return false;
            }
        }
    }
};
?>