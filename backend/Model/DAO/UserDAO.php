<?php
require_once "Database.php";

class UserDAO
{
    public function fetch_password($user_id)
    {

        $conn = new ConnectionManager();
        $pdo = $conn->getConnection(); #important i did not implement exception catch

        $sql = "SELECT pw_hash from user where user_id= :uid";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_NUM);

        $result = $stmt->fetchAll(); #shd be one

        $stmt = null;
        $pdo = null;

        return $result[0][0];
    }

    public function fetch_name($user_id)
    {

        $conn = new ConnectionManager();
        $pdo = $conn->getConnection(); #important i did not implement exception catch

        $sql = "SELECT firstname,lastname from user where user_id= :uid";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_NUM);

        $result = $stmt->fetchAll(); #shd be one

        $stmt = null;
        $pdo = null;

        return $result[0];
    }

    public function add_user($firstname, $email, $pw_hash, $lastname = NULL)
    { #note the not normal ordering
#note for joshua double quotes
        $conn = new ConnectionManager();
        $pdo = $conn->getConnection(); #important i did not implement exception catch

        $sql = "insert into user (firstname,lastname,email,pw_hash) values (?,?,?,?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($firstname, $lastname, $email, $pw_hash));

        $rows = $stmt->rowCount(); #null how

        $stmt = null;
        $pdo = null;

        return $rows;
    }

    public function fetch_by_email($email)
    {

        $conn = new ConnectionManager();
        $pdo = $conn->getConnection();

        $sql = "SELECT firstname,lastname,user_id,pw_hash, email from user where email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $result = $stmt->fetchAll(); #shd be one

        $stmt = null;
        $pdo = null;

        return $result;
    }

    public function verify_user($email, $password)
    {
        $user_info = $this->fetch_by_email($email);
        if (count($user_info) == 1)
        {
            $user_info = $user_info[0];
            return password_verify($password, $user_info['pw_hash']) ? $user_info : false;
        }
        return false;
        
    }
}

?>