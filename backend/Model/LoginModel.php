<?php

include_once(__DIR__ . "/../inc/config.php");
spl_autoload_register(
    function ($class)
    {
        require_once "DAO/$class.php";
    }
);

class Login
{





    public function authenticate(string $email, string $password): array |bool
    {
        $DAO = new UserDAO();
        if ($DAO->verify_user($email, $password, NULL))
        {
            $user_information = $DAO->fetch_by_email($email);
            return array('email' => $email, 'user_id' => $user_information['user_id']);
        }
        return false;

    }

    public function createSession(int $user_id, $device = NULL, string $token, int $issued_at, int $expires_at): bool
    {
        $DAO = new SessionDAO();

        if ($DAO->add_session($user_id, $device, $token, $expires_at) == 1)
        {
            return true;
        }
        return false;
    }



}
?>