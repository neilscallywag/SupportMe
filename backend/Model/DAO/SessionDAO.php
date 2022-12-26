<?php
require_once 'Database.php';

class SessionDAO
{
    public function delete_expired_session()
    {

        $conn = new ConnectionManager();
        $pdo = $conn->getConnection(); #important i did not implement exception catch

        $sql = 'delete from session where timestampdiff(second,now(),session.TTL)<0';
        $stmt = $pdo->prepare($sql);


        $stmt->execute();

        if ($stmt->rowCount()) {
            return true;
        }
        ;

        return false;
    }
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
        $this->delete_expired_session();
        return $result;
    }

    public function checkSessionByJWT($session_data)
    {

        $conn = new ConnectionManager();
        $pdo = $conn->getConnection(); #important i did not implement exception catch

        $sql = "SELECT (timestampdiff(second,now(),s.TTL)<0) as expired, device, user_id from session s where session_data = :sid";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':sid', $session_data, PDO::PARAM_STR);
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $result = $stmt->fetchAll();


        $stmt = null;
        $pdo = null;

        if (empty($result)) { #case one no such session exist
            return false;
        } else {
            $result = $result[0];
            return boolval($result['expired']) ? false : $result; #case 2 it is expired
        }
    }

    /**
     * This function fetches session data from the database given user_id and device type.
     * Its based on the zCheckSessionByJWT() function implemented by Joshua
     * @author Neil 
     * 
     * @param string $user_id is the user identifier
     * @param array $device is the device identifier
     * 
     * @return bool
     */
    public function CheckSessionByUID(int $user_id, string $device)
    {
        $conn = new ConnectionManager();
        $pdo = $conn->getConnection();

        $sql = "SELECT (timestampdiff(second,now(),s.TTL)<0) as expired, device, user_id from session s where user_id = :uid and device = :device";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':device', $device, PDO::PARAM_STR);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();
        $stmt = null;
        $pdo = null;
        if (empty($result)) { #case one no such session exist
            return false;
        } else {
            $result = $result[0];
            return boolval($result['expired']) ? false : $result; #case 2 it is expired
        }
    }
}

?>