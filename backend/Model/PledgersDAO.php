<?php
require_once 'Database.php';

class PledgersDAO {
    public function fetch_pledgers($campaign_id){

        $conn= new ConnectionManager();
        $pdo = $conn->getConnection(); #important i did not implement exception catch
    
        $sql = "SELECT p.pledger_id,u.firstname,u.lastname,p.createdat from pledgers p inner join user u on p.pledger_id=u.user_id where p.campaign_id = :cid";
        $stmt = $pdo->prepare($sql);
        $stmt-> bindParam(':cid',$campaign_id,PDO::PARAM_INT);
        $stmt-> execute();

        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $result = $stmt->fetchAll(); 

        $stmt = null;
        $pdo = null;

        return $result;
    }

    public function add_pledge($pledger_id,$campaign_id){   
        $conn= new ConnectionManager();
        $pdo = $conn->getConnection(); #important i did not implement exception catch
    
        $sql = "insert into pledgers (pledger_id,campaign_id) values (?,?)";
        $stmt = $pdo->prepare($sql);
        $stmt-> execute(array($pledger_id,$campaign_id));

        $rows = $stmt->rowCount();

        $stmt = null;
        $pdo = null;

        return $rows;
    }

};

?>
