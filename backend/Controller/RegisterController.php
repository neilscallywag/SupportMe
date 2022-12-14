<?php
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
    public function Register($name, $username, $password, $email)
    {
        if ($_SERVER["REQUEST_METHOD"] != "POST") {
            $this->sendOutput("", array("HTTP/1.1 404 Not Found"));
        } else {
            $this->sendOutput("Test", array("HTTP/1.1 200 OK"));
        }
    }



}


?>