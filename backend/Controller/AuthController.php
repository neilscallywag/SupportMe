<?php
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;

include_once(__DIR__ . "/BaseController.php");
include_once(__DIR__ . "/../inc/config.php");

class AuthController extends BaseController
{

    private string $JWT;
    private int $user_id;
    private string $bearer;
    private string $device;


    /**
     * This function checks if the authentication header is valid
     * @author Neil 
     * 
     * @param array $headers are headers from the request
     * 
     * @return bool
     * @return 401 if JWT token validation fails 
     * 
     */

    //TODO: 1. Create a logger function to log the exceptions
    //      2. Clean up the SQL as TTL may not necessarily be required. 
    public function CheckGivenToken(array $headers): bool
    {
        if (empty($headers)) {
            return false;

        } else if (!array_key_exists("Authorization", $headers) || !array_key_exists("User-Agent", $headers)) {
            $response = json_encode(array("error" => HEADER_MISSING));
            $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
            return false;

        } else {
            include_once __DIR__ . '/../vendor/autoload.php';

            try {
                // Decode JWT token
                $decoded = JWT::decode($jwt, new Key(PRIVATE_KEY, 'HS256'));
                $decoded_array = (array) $decoded;
                $this->JWT = explode(" ", $headers['Authorization'])[1];
                $this->bearer = explode(" ", $headers['Authorization'])[0];
                $this->device = $headers['User-Agent'];
                $this->user_id = $decoded_array['user_id'];

                // Check if the provided bearer is valid bearer
                if ($this->bearer != ISSUER) {
                    $response = json_encode(array("error" => TOKEN_INVALID));
                    $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 401 Authentication Error'));
                    return false;
                }

                // Check if the Session exists in the database. 
                include __DIR__ . "/../Model/DAO/SessionDAO.php";
                $DAO = new SessionDAO();
                $user_data = $DAO->fetch_session($this->JWT);

                // Check if the user ID provided in from the token corresponds the the user ID in the session data
                if (is_array($user_data)) {

                    if ($user_data['user_id'] == $this->user_id && $user_data['User-Agent'] == $this->device) {
                        return true;
                    }

                } else {
                    $response = json_encode(array("error" => TOKEN_INVALID));
                    $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 401 Authentication Error'));
                    return false;
                }

            } catch (InvalidArgumentException $e) {
                // provided key/key-array is empty or malformed.
                $response = json_encode(array("error" => TOKEN_INVALID));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 401 Authentication Error'));
                return false;

            } catch (DomainException $e) {
                // provided algorithm is unsupported OR
                // provided key is invalid OR
                // unknown error thrown in openSSL or libsodium OR
                // libsodium is required but not available.
                $response = json_encode(array("error" => TOKEN_INVALID));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 401 Authentication Error'));
                return false;

            } catch (SignatureInvalidException $e) {
                // provided JWT signature verification failed.
                $response = json_encode(array("error" => TOKEN_INVALID));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 401 Authentication Error'));
                return false;

            } catch (BeforeValidException $e) {
                // provided JWT is trying to be used before "nbf" claim OR
                // provided JWT is trying to be used before "iat" claim.
                $response = json_encode(array("error" => TOKEN_INVALID));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 401 Authentication Error'));
                return false;

            } catch (ExpiredException $e) {
                // provided JWT is trying to be used after "exp" claim.
                $response = json_encode(array("error" => TOKEN_INVALID));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 401 Authentication Error'));
                return false;

            } catch (UnexpectedValueException $e) {
                // provided JWT is malformed OR
                // provided JWT is missing an algorithm / using an unsupported algorithm OR
                // provided JWT algorithm does not match provided key OR
                // provided key ID in key/key-array is empty or invalid.
                $response = json_encode(array("error" => TOKEN_INVALID));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 401 Authentication Error'));
                return false;
            }

            $response = json_encode(array("error" => TOKEN_INVALID));
            $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 401 Authentication Error'));
            return false;
        }
    }
}
;
?>