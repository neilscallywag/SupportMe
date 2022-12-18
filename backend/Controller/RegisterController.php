<?php

include_once(__DIR__ . "/BaseController.php");
include_once(__DIR__ . "/../inc/config.php");

class RegisterController extends BaseController
{

    private string $firstname;
    private string $lastname;
    private string $email;
    private string $password;



    /**
     * This function takes in a JSON string and creates a new user.
     * @author Neil 
     * 
     * @param string $data is a JSON string
     * 
     * @var int     data["firstname"]   : is the user's first name.
     * @var string  data["lastname"]    : is the user's last name. It is optional.
     * @var string  data["email"]       : is the user's email address.
     * @var string  data["password"]    : is the user's password.
     * 
     * @return void
     * @return 200 if successfully created a new campaign
     * @return 400 if missing fields or validation failure. 
     * @return 500 if database error
     */

    public function Register(string $data): void
    {
        // if provided JSON string is empty, return empty json error.
        if (empty($data)) {
            $response = json_encode(array("error" => EMPTY_JSON));
            $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));

        } else {
            // Convert the JSON string into an associative array and populat the class variables.
            $data = json_decode($data);
            $this->firstname = $data->firstname;
            $this->lastname = $data->lastname;
            // Sanitize the Email String using built-in method.
            $this->email = filter_var($data->email, FILTER_SANITIZE_EMAIL);
            $this->password = $data->password;

            // return error if empty firstname
            if (empty($this->firstname)) {
                $response = json_encode(array("error" => EMPTY_FIRSTNAME_ERROR));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
                // return error if empty email    
            } elseif (empty($this->email)) {
                $response = json_encode(array("error" => EMPTY_EMAIL_ERROR));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
                // return error if empty password
            } elseif (empty($this->password) or !$this->password) {
                $response = json_encode(array("error" => EMPTY_PASSWORD_ERROR));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
                // return error if malformed email  
            } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                $response = json_encode(array("error" => INVALID_EMAIL));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
            } else {
                // call the Register Model and DAO to register the new user. 
                require_once __DIR__ . '\..\Model\RegisterModel.php';
                $create = new Register();
                $response = $create->create($this->firstname, $this->lastname, $this->email, $this->password);

                if (array_key_exists("error", json_decode($response))) {
                    $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 500'));
                } else {
                    $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));

                }
            }

        }
    }



}

?>