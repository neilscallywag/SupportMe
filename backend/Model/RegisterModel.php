<?php

include_once(__DIR__ . "/../inc/config.php");
include(__DIR__ . "/DAO/UserDAO.php");

class Register
{



    private function doesEmailExist($email)
    {
        $DAO = new UserDAO();
        return boolval($DAO->fetch_by_email($email));

    }

    public function create($firstname, $lastname, $email, $password)
    {
        if ($this->doesEmailExist($email))
        {
            $response = json_encode(array("error" => EMAIL_EXISTS));
            return $response;
        }
        ;

        $firstname = htmlspecialchars(strip_tags($firstname)); #qn by joshua whts the pt of html special chars
        $lastname = htmlspecialchars(strip_tags($lastname));
        $email = htmlspecialchars(strip_tags($email));
        $password = htmlspecialchars(strip_tags($password));



        try
        {
            $dao = new UserDAO();
            $dao->add_user($firstname, $email, password_hash($password, PASSWORD_DEFAULT), $lastname);
            $response = json_encode(array("message" => SUCCESS));
        }
        catch (Exception $e)
        {
            $response = json_encode(array("error" => SQL_FAIL));
        }
        return $response;


    }


}
?>