<?php
class Register extends database
{
    private const SQL_FAIL = "SQL Error";

    private const SUCCESS = "Successfully registered";

    private const EMAIL_EXISTS = "Email already exists";

    public function create($firstname, $lastname, $email, $password): bool|string
    {
        $db = new Database();
        $query = "INSERT INTO users SET
                firstname = :firstname,
                lastname = :lastname,
                email = :email,
                password = :password";
        $firstname = htmlspecialchars(strip_tags($firstname));
        $lastname = htmlspecialchars(strip_tags($lastname));
        $email = htmlspecialchars(strip_tags($email));
        $password = htmlspecialchars(strip_tags($password));

        if (doesEmailExist($email) >0 ){
            $response = json_encode(array("error" => self::EMAIL_EXISTS));
        }


        $params = array($firstname, $lastname, $email, $password);
        try {
            $db->Execute($query, $params);
            $response = json_encode(array("message" => self::SUCCESS));
        } catch (Exception $e) {
            $response = json_encode(array("error" => self::SQL_FAIL));

        }
        return $response


    }

    private function doesEmailExist($email)
    { 
        $db = new Database();
        $query = "SELECT email FROM users WHERE email = :email";
        return $db->Fetch_One($query, $email);
    
    }
}
?>