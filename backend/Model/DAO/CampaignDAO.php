<?php
require_once 'Database.php';

class CampaignDAO {
    public function fetch_campaign($campaign_id){

        $conn= new ConnectionManager();
        $pdo = $conn->getConnection(); #important i did not implement exception catch
    
        $sql = "SELECT starter_id,c_title,c_description,c_picture from campaign where campaign_id= :cid";
        $stmt = $pdo->prepare($sql);
        $stmt-> bindParam(':cid',$campaign_id,PDO::PARAM_INT);
        $stmt-> execute();

        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $result = $stmt->fetchAll();  #shd be one

        $stmt = null;
        $pdo = null;

        return $result;
    }

    public function search_campaign($campaign_name){

        $conn= new ConnectionManager();
        $pdo = $conn->getConnection(); 
    
        $sql = "SELECT starter_id,c_title,c_description,c_picture from campaign where c_title like :cname";
        $stmt = $pdo->prepare($sql);

        $campaign_name = '%'.$campaign_name.'%';
        $stmt-> bindParam(':cname',$campaign_name,PDO::PARAM_STR);
        $stmt-> execute();

        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $result = $stmt->fetchAll();  #shd be one

        $stmt = null;
        $pdo = null;

        return $result;
    }

    public function add_campaign($starter_id,$c_title,$c_description=NULL,$c_picture=NULL){   
        $conn= new ConnectionManager();
        $pdo = $conn->getConnection(); #important i did not implement exception catch
    
        $sql = "insert into campaign (starter_id,c_title,c_description,c_picture) values (?,?,?,?)";
        $stmt = $pdo->prepare($sql);
        $stmt-> execute(array($starter_id,$c_title,$c_description,$c_picture));

        $rows = $stmt->rowCount();

        $stmt = null;
        $pdo = null;

        return $rows;
    }

    public function fetch_user_campaign($user_id){

        $conn= new ConnectionManager();
        $pdo = $conn->getConnection(); #important i did not implement exception catch
    
        $sql = "SELECT c_title,c_description,c_picture from campaign where starter_id= :uid";
        $stmt = $pdo->prepare($sql);
        $stmt-> bindParam(':uid',$user_id,PDO::PARAM_INT);
        $stmt-> execute();

        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $result = $stmt->fetchAll();  #shd be one

        $stmt = null;
        $pdo = null;

        return $result;
    }

}
?>