<?php

include_once(__DIR__ . "/BaseController.php");

/**
 * Summary of RegisterController
 */
class RegisterController extends BaseController
{

    private string $firstname;
    private string $lastname;
    private string $email;
    private string $password;

    private const EMPTY_JSON = "Invalid JSON passed to register";
    private const EMPTY_FIRSTNAME_ERROR = "Empty firstname";
    private const EMPTY_EMAIL_ERROR = "Empty email";
    private const EMPTY_PASSWORD_ERROR = "Empty password";

    private const INVALID_EMAIL = "Invalid email address";

    private const PASSWORD_CHECK_ERROR = "Passwords do not match";

    /**
     * Register a new user
     * @param string $data : $data is a JSON array
     * @return void
     */
    public function Register(string $data): void
    {

        if (empty($data)) {
            $response = json_encode(array("error" => self::EMPTY_JSON));
            $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));

        } else {
            $data = json_decode($data);
            // set product property values
            $this->firstname = $data->firstname;
            $this->lastname = $data->lastname;
            $this->email = filter_input(INPUT_POST, $data->email, FILTER_SANITIZE_EMAIL);
            $this->password = $data->password;
            $this->password = password_hash($this->password, PASSWORD_ARGON2I);
            if (empty($this->firstname)) {
                $response = json_encode(array("error" => self::EMPTY_FIRSTNAME_ERROR));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
            } elseif (empty($this->email)) {
                $response = json_encode(array("error" => self::EMPTY_EMAIL_ERROR));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
            } elseif (empty($this->password) or !$this->password) {
                $response = json_encode(array("error" => self::EMPTY_PASSWORD_ERROR));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
            } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                $response = json_encode(array("error" => self::INVALID_EMAIL));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
            } else {
                require_once '../Model/RegisterModel.php';
                $create = new Register();
                $response = $create->create($this->firstname, $this->lastname, $this->email, $this->password);

                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
            }

        }
    }



}

?>