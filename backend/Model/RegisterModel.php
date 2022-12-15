<?php
class Register extends Database
{
    private const SQL_FAIL = "SQL Error";

    private const SUCCESS = "Successfully registered";

    private const EMAIL_EXISTS = "Email already exists";

    /**
     * Creates a new user in the database
     * @param string $firstname
     * @param string $lastname
     * @param string $email
     * @param string $password
     * @return bool|string
     */
    public function create(string $firstname, string $lastname, string $email, string $password): bool|string
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

        if (self::doesEmailExist($email) > 0) {
            $response = json_encode(array("error" => self::EMAIL_EXISTS));
        }


        $params = array($firstname, $lastname, $email, $password);
        try {
            $db->Execute($query, $params);
            $response = json_encode(array("message" => self::SUCCESS));
        } catch (Exception $e) {
            $response = json_encode(array("error" => self::SQL_FAIL));

        }
        return $response;


    }

    /**
     * Checks if the given email exists in the database
     * @param string $email
     * @return int
     */
    private function doesEmailExist(string $email)
    {
        $db = new Database();
        $query = "SELECT email FROM users WHERE email = :email";
        return count($db->FetchOne($query, $email));

    }
}
?>