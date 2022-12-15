<?php


spl_autoload_register(
    function($class) {
        require_once "$class.php";
    }
);

class Register{


    private const SQL_FAIL = "SQL Error";

    private const SUCCESS = "Successfully registered";

    private const EMAIL_EXISTS = "Email already exists";


    private function doesEmailExist($email)
    { 
        $DAO=new UserDAO();
        return boolval($DAO->fetch_by_email($email));
    
    }

    public function create($firstname, $lastname, $email, $password) 

    {
        if ($this->doesEmailExist($email) ){
            $response = json_encode(array("error" => self::EMAIL_EXISTS));
            return $response;
        };

        $firstname = htmlspecialchars(strip_tags($firstname)); #qn by joshua whts the pt of html special chars
        $lastname = htmlspecialchars(strip_tags($lastname));
        $email = htmlspecialchars(strip_tags($email));
        $password = htmlspecialchars(strip_tags($password));



        try {
            $dao = new UserDAO();
            $dao->add_user($firstname,$email,password_hash($password,PASSWORD_DEFAULT),$lastname);
            $response = json_encode(array("message" => self::SUCCESS));
        } catch (Exception $e) {
            $response = json_encode(array("error" => self::SQL_FAIL));
        }
        return $response;


    }


}
?>