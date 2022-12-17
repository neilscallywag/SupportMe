<?php
include_once(__DIR__ . "/BaseController.php");
include_once(__DIR__ . "/../inc/config.php");

class AuthController extends BaseController
{

    private string $JWT;
    private string $email;
    private string $device;

    private int $user_id;

    public function CheckGivenToken($headers, $data)
    {
        #headers check implemented
        if (empty($data))
        { #issue to resolve why 200 OK even tho error
            $response = json_encode(array("error" => EMPTY_JSON));
            $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));

        }
        else if (!array_key_exists("Authorization",$headers) || !array_key_exists("Device",$headers) )
        {
            $response = json_encode(array("error" => HEADER_MISSING));
            $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
        } else if (empty($data["user_id"])) {
            $response = json_encode(array("error" => EMPTY_USER_ERROR));
            $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 400 '));
        } else {
            $data = json_decode($data,true);
            $this->JWT = $headers['Authorization'];
            $this->device=$headers['Device'];
            $this->user_id = $data['user_id'];

            include __DIR__ . "/../Model/DAO/SessionDAO.php";
            $DAO = new SessionDAO();
            $user_data = $DAO->fetch_session($this->JWT);
            if (is_array($user_data))
            {
                if ($user_data['user_id'] == $this->user_id && $user_data['device']== $this->device)
                {
                    return true;
                }
            }
            else
            {
                $response = json_encode(array("error" => TOKEN_INVALID));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 401 Authentication Error'));
                return false;
            }
       }
    }
}
;
?>