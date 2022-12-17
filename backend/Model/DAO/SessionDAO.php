<?php
require_once 'Database.php';

class SessionDAO
{
    public function delete_expired_session (){

        $conn = new ConnectionManager();
        $pdo = $conn->getConnection(); #important i did not implement exception catch

        $sql = 'delete from session where timestampdiff(second,now(),session.TTL)<0';
        $stmt = $pdo->prepare($sql);


        $stmt->execute();

        if ($stmt->rowCount()){
            return true;
        };

        return false;
    }
    public function add_session($user_id, $device, $session_data, $TTL)
    {

        $conn = new ConnectionManager();
        $pdo = $conn->getConnection(); #important i did not implement exception catch

        $sql = 'select (timestampdiff(second,now(),s.TTL)<0) as expired, session_data from session s where user_id= :uid and device =:device';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':uid',$user_id);
        $stmt->bindParam(':device',$device);

        $stmt->execute();

        if ($stmt->rowCount()){
            while ($one_session=$stmt->fetch()){
                if ($one_session['expired']==0){
                    $stmt=null;
                    $pdo=null;
                    $this->delete_expired_session();
                    return false;
                    
                }
            }
        }

        #okay can add
        $sql = "insert into session(user_id,device,session_data,TTL) values (?,?,?,?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($user_id, $device, $session_data, $TTL));


        $result = $stmt->rowCount();

        $stmt = null;
        $pdo = null;

        $this->delete_expired_session();

        return $result;
    }

    public function fetch_session($session_data)
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

        if (empty($result)){ #case one no such session exist
            return false;
        }  else {
            $result=$result[0];
            return boolval($result['expired']) ? false : $result; #case 2 it is expired
        }
    }
}

?>