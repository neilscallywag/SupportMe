<?php
use Firebase\JWT\JWT;

include_once(__DIR__ . "/BaseController.php");
include_once(__DIR__ . "/../inc/config.php");
class LoginController extends BaseController
{

    private string $email;

    private string $password;

    # JWT authentication token
    private string $token;


    public function Login(string $data)
    {
        if (empty($data))
        {
            $response = json_encode(array("error" => EMPTY_JSON));
            $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));

        }
        else
        {
            $data = json_decode($data);
            // foreach ($data as $key => $value)
            // {
            //     $this->sendOutput(".$key.:.$value.", array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
            // }

            $this->email = filter_var($data->email, FILTER_SANITIZE_EMAIL);
            $this->password = $data->password;
            $this->password = password_hash($this->password, PASSWORD_ARGON2I);

            if (empty($this->email))
            {
                $response = json_encode(array("error" => EMPTY_EMAIL_ERROR));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));

            }
            elseif (empty($this->password) or !$this->password)
            {
                $response = json_encode(array("error" => EMPTY_PASSWORD_ERROR));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));

            }
            else
            {

                require_once __DIR__ . '\..\Model\LoginModel.php';
                $login = new Login();

                $response = $login->authenticate($this->email, $this->password); # Array | Boolean (if failed login)
                if (gettype($response) == "array")
                {

                    $issued_at = time();
                    $expiration_time = $issued_at + (60 * 60); // valid for 1 hour


                    include_once '../vendor/autoload.php';
                    include_once '../inc/config.php';

                    $this->token = array(
                        "iat" => $issued_at,
                        "exp" => $expiration_time,
                        "iss" => ISSUER,
                        "data" => array("email" => $response["email"], "user_id" => $response["user_id"])
                    );

                    $JWT = JWT::encode($this->token, PRIVATE_KEY, 'HS256');

                    // Method createSession. Params: UserID, Device , Token, issuedAt ExpirationTime
                    if ($login->createSession($respnse["user_id"], NULL, $JWT, $issued_at, $expiration_time))
                    {
                        $output = json_encode(array("message" => LOGIN_SUCCESS, "token" => $JWT));
                        $this->sendOutput($output, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
                    }

                }
                $response = json_encode(array("error" => LOGIN_FAILED));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 401 Authentication Error'));

            }
        }

    }
}
?>