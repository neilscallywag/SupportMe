<?php
include_once(__DIR__ . "/BaseController.php");
include_once(__DIR__ . "/../inc/config.php");

class AuthController extends BaseController
{

    private string $JWT;
    private string $email;

    private int $user_id;

    public function CheckGivenToken($token, $data)
    {
        if (empty($data) or empty($token))
        {
            $response = json_encode(array("error" => EMPTY_JSON));
            $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));

        }
        else
        {
            $data = json_decode($data);
            $this->JWT = $token;
            $this->user_id = $data['user_id'];

            include __DIR__ . "/../Model/DAO/SessionDAO.php";
            $DAO = new SessionDAO();
            $user_data = $DAO->fetch_session($this->JWT);

            if (is_array($user_data))
            {
                if ($user_data['user_id'] == $this->user_id)
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