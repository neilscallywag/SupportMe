<?php
require_once 'Database.php';

class PledgersDAO {
    public function fetch_pledge($campaign_id){

        $conn= new ConnectionManager();
        $pdo = $conn->getConnection(); #important i did not implement exception catch
    
        $sql = "SELECT p.pledger_id,u.firstname,u.lastname,p.createdat, p.pledge_reason from pledgers p inner join user u on p.pledger_id=u.user_id where p.campaign_id = :cid";
        $stmt = $pdo->prepare($sql);
        $stmt-> bindParam(':cid',$campaign_id,PDO::PARAM_INT);
        $stmt-> execute();

        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $result = $stmt->fetchAll(); 

        $stmt = null;
        $pdo = null;

        return $result;
    }

    public function count_pledge($campaign_id){

        $conn= new ConnectionManager();
        $pdo = $conn->getConnection(); #important i did not implement exception catch
    
        $sql = "SELECT count(pledger_id) from pledgers p where p.campaign_id = :cid";
        $stmt = $pdo->prepare($sql);
        $stmt-> bindParam(':cid',$campaign_id,PDO::PARAM_INT);
        $stmt-> execute();

        $stmt->setFetchMode(PDO::FETCH_NUM);

        $result = $stmt->fetchAll(); 

        $stmt = null;
        $pdo = null;

        return $result[0][0];
    }

    public function check_pledge($user_id, $campaign_id){

        $conn= new ConnectionManager();
        $pdo = $conn->getConnection(); #important i did not implement exception catch
    
        $sql = "SELECT 1 from pledgers p where p.campaign_id = :cid and p.pledger_id = :uid";
        $stmt = $pdo->prepare($sql);
        $stmt-> bindParam(':cid',$campaign_id,PDO::PARAM_INT);
        $stmt-> bindParam(':uid',$user_id,PDO::PARAM_INT);
        $stmt-> execute();

        $stmt->setFetchMode(PDO::FETCH_NUM);

        $result = $stmt->fetchAll(); 

        $stmt = null;
        $pdo = null;

        return boolval($result);
    }

    public function add_pledge($pledger_id,$campaign_id,$pledge_reason){   
        $conn= new ConnectionManager();
        $pdo = $conn->getConnection(); #important i did not implement exception catch
    
        $sql = "insert into pledgers (pledger_id,campaign_id,pledge_reason) values (?,?,?)";
        $stmt = $pdo->prepare($sql);
        $stmt-> execute(array($pledger_id,$campaign_id,$pledge_reason));

        $rows = $stmt->rowCount();

        $stmt = null;
        $pdo = null;

        return boolval($rows);
    }

    public function delete_pledge($pledger_id,$campaign_id){   
        $conn= new ConnectionManager();
        $pdo = $conn->getConnection(); #important i did not implement exception catch
    
        $sql = "delete from pledgers where pledger_id = :uid  and campaign_id = :cid";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':uid',$pledger_id,PDO::PARAM_INT);
        $stmt->bindParam(':cid',$campaign_id,PDO::PARAM_INT);
        $stmt-> execute();

        $rows = $stmt->rowCount();

        $stmt = null;
        $pdo = null;

        return boolval($rows);
    }

};

?>
