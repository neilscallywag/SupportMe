<?php
use Firebase\JWT\JWT;

include_once(__DIR__ . "/BaseController.php");
class LoginController extends BaseController
{

    private string $email;

    private string $password;

    # JWT authentication token
    private string $token;

    private const EMPTY_EMAIL_ERROR = 'Please enter an email address';
    private const EMPTY_PASSWORD_ERROR = 'Please enter a password';
    private const INVALID_AUTHENTICATION_TOKEN = 'Invalid authentication token';
    private const EMPTY_JSON = "Invalid JSON passed to register";

    private const LOGIN_SUCCESS = 'Login successful';

    private const LOGIN_FAILED = 'Login failed';

    public function Login(string $data)
    {
        if (empty($data)) {
            $response = json_encode(array("error" => self::EMPTY_JSON));
            $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));

        } else {
            $data = json_decode($data);
            $this->email = filter_input(INPUT_POST, $data->email, FILTER_SANITIZE_EMAIL);
            $this->password = $data->password;
            $this->password = password_hash($this->password, PASSWORD_ARGON2I);

            if (empty($this->email)) {
                $response = json_encode(array("error" => self::EMPTY_EMAIL_ERROR));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));

            } elseif (empty($this->password) or !$this->password) {
                $response = json_encode(array("error" => self::EMPTY_PASSWORD_ERROR));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));

            } else {

                require_once '../Model/LoginModel.php';
                $login = new Login();
                //TODO: Create authenticate method
                $response = $login->authenticate($this->email, $this->password); # Array | Boolean (if failed login)

                if (gettype($response) == "array") {

                    $issued_at = time();
                    $expiration_time = $issued_at + (60 * 60); // valid for 1 hour


                    include_once '../vendor/firebase/php-jwt/src/BeforeValidException.php';
                    include_once '../vendor/firebase/php-jwt/src/ExpiredException.php';
                    include_once '../vendor/firebase/php-jwt/src/SignatureInvalidException.php';
                    include_once '../vendor/firebase/php-jwt/src/JWT.php';
                    include_once '../inc/config.php';

                    $this->token = array(
                        "iat" => $issued_at,
                        "exp" => $expiration_time,
                        "iss" => ISSUER,
                        "data" => array(
                            "id" => $response->id,
                            "email" => $response->email
                        )
                    );

                    $JWT = JWT::encode($this->token, PRIVATE_KEY, 'HS256');
                    $output = json_encode(
                        array(
                            "message" => self::LOGIN_SUCCESS,
                            "token" => $JWT
                        )
                    );
                    $this->sendOutput($output, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));

                    //TODO: Make this fucntion in the Login Model. 
                    // Method createSession. Params: UserID, Token, issuedAt ExpirationTime 
                    $login->createSession($response->id, $JWT, $issued_at, $expiration_time);
                }
                $response = json_encode(array("error" => self::LOGIN_FAILED));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 401 Authentication Error'));


            }
        }


    }
}
?>