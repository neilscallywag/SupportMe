<?php
require_once 'Database.php';

class SessionDAO
{
    public function add_session($user_id, $device, $session_data, $TTL)
    {

        $conn = new ConnectionManager();
        $pdo = $conn->getConnection(); #important i did not implement exception catch

        $sql = "insert into session(user_id,device,session_data,TTL) values (?,?,?,?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($user_id, $device, $session_data, $TTL));


        $result = $stmt->rowCount();

        $stmt = null;
        $pdo = null;

        return $result;
    }

    public function fetch_session($session_data)
    {

        $conn = new ConnectionManager();
        $pdo = $conn->getConnection(); #important i did not implement exception catch

        $sql = "SELECT (timestampdiff(second,now(),s.TTL)<0) as expired, device, createdat, TTL ,user_id from session s where session_data = :sid";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':sid', $session_data, PDO::PARAM_STR);
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $result = $stmt->fetchAll();

        $stmt = null;
        $pdo = null;

        $result[0]['expired'] = boolval($result[0]['expired']);
        return $result[0];
    }
}

?>