<?php

include_once(__DIR__ . "/../inc/config.php");
include(__DIR__ . "/DAO/UserDAO.php");
include(__DIR__ . "/DAO/SessionDAO.php");

class Login
{
    public function authenticate(string $email, string $password)
    {
        $DAO = new UserDAO();
        return ($DAO->verify_user($email, $password));
        #verify user returns either false for no user/pass wrong
    }

    public function createSession(int $user_id, string $device, string $token, int $issued_at, string $expires_at): bool
    {
        $DAO = new SessionDAO();
        return boolval($DAO->add_session($user_id, $device, $token, $expires_at) == 1);
    }
}
?>