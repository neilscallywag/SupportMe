<?php
use Firebase\JWT\JWT;

include_once(__DIR__ . "/BaseController.php");
include_once(__DIR__ . "/../inc/config.php");
class LoginController extends BaseController
{

    private string $email;
    private string $password;
    private array $token;
    private string $device;

    /**
     * This function logs the user in. 
     * @author Neil 
     * 
     * @param string $data is a JSON string
     * @param array $headers are http headers
     * 
     * @return void
     * @return 200 if successfully logged in 
     * @return 400 if missing fields or incorrect length of title or description
     * @return 401 if login failed
     */

    public function Login(string $data, array $headers): void
    {
        /*
        Test out contents of headers array
        $test = json_encode($headers);
        $this->sendOutput($test, array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
        */

        // Check if the JSON string is empty
        if (empty($data)) {
            $response = json_encode(array("error" => EMPTY_JSON));
            $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
            // Check if User-Agent key exists in the header.     
        } else if (!array_key_exists('User-Agent', $headers)) {
            $response = json_encode(array("error" => HEADER_MISSING));
            $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
        } else {
            // Decode the JSON string into array 
            $data = json_decode($data);
            // Sanitize the email address
            $this->email = filter_var($data->email, FILTER_SANITIZE_EMAIL);
            // associate the class variables with the data given 
            $this->password = $data->password;
            $this->device = $headers['User-Agent'];

            // check if the email is empty
            if (empty($this->email)) {
                $response = json_encode(array("error" => EMPTY_EMAIL_ERROR));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));

                // check if the password is empty
            } elseif (empty($this->password) or !$this->password) {
                $response = json_encode(array("error" => EMPTY_PASSWORD_ERROR));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));

            } else {
                // authenticate the credentials
                require_once __DIR__ . '\..\Model\LoginModel.php';
                $login = new Login();
                $response = $login->authenticate($this->email, $this->password); # Array | Boolean (if failed login)

                if (gettype($response) == "array") {
                    // Do not proceed if the session does exist 
                    if ($login->checkSessionbyUID($response['user_id'],$this->device)) {
                        $response = json_encode(array("error" => SESSION_EXISTS_ERROR));
                        $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
                    } else {

                        // create a new session
                        $issued_at = time();
                        $expiration_time = $issued_at + (60 * 60); // valid for 1 hour
                        $expiration_str = date('Y-m-d H:i:s e', $expiration_time);

                        include_once __DIR__ . '/../vendor/autoload.php';
                        include_once __DIR__ . '/../inc/config.php';

                        $this->token = array(
                            "iat" => $issued_at,
                            "exp" => $expiration_time,
                            "iss" => ISSUER,
                            "data" => array("email" => $response["email"], "user_id" => $response["user_id"])
                        );
                        // JWT encoded response
                        $JWT = JWT::encode($this->token, PRIVATE_KEY, 'HS256');

                        //Method createSession. Params: UserID, Device , Token, issuedAt ExpirationTime
                        if ($login->createSession($response["user_id"], $this->device, $JWT, $issued_at, $expiration_str)) {
                            $output = json_encode(array("message" => LOGIN_SUCCESS, "token" => $JWT));
                            $this->sendOutput($output, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
                        }
                    }

                }

                #fail but why, does user exist? is it wrong pass?
                $response = json_encode(array("error" => LOGIN_FAILED));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 401 Authentication Error'));

            }
        }

    }
}
?>