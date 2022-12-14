<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once(__DIR__ . "/BaseController.php");
class RegisterController extends BaseController
{
    /**
     * Summary of Register
     * @param mixed $name
     * @param mixed $username
     * @param mixed $password
     * @param mixed $email
     * @return void
     */


    private string $firstname;
    private string $lastname;
    private string $email;
    private string $password;

    public function Register($data)
    {

        if (empty($data)) {
            $this->sendOutput("Could not create user", array("HTTP/1.1 200 OK"));
        } else {

            // set product property values
            $this->firstname = $data->firstname;
            $this->lastname = $data->lastname;
            $this->email = $data->email;
            $this->password = $data->password;
            $this->console_log($this->firstname);
            $this->console_log($this->lastname);
            $this->console_log($this->email);
            $this->console_log($this->password);
            $this->console_log($data);
            if (
                !empty($this->firstname) &&
                !empty($this->email) &&
                !empty($this->password)

            ) {
                $this->create();
            }
        }
    }

    private function create()
    {
        require "Model/Database.php";
        $db = new Database();
        $query = "INSERT INTO users SET
                firstname = :firstname,
                lastname = :lastname,
                email = :email,
                password = :password";
        $this->firstname = htmlspecialchars(strip_tags($this->firstname));
        $this->lastname = htmlspecialchars(strip_tags($this->lastname));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));

        $params = array($this->firstname, $this->lastname, $this->email, $this->password);
        try {
            $db->Execute($query, $params);
            $this->sendOutput("User Created", array("HTTP/1.1 200 OK"));
        } catch (Exception $e) {
            $this->sendOutput("Could not crseate user", array("HTTP/1.1 200 OK"));

        }


    }
    private function console_log($output, $with_script_tags = true)
    {
        $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) .
            ');';
        if ($with_script_tags) {
            $js_code = '<script>' . $js_code . '</script>';
        }
        echo $js_code;
    }


}


?>