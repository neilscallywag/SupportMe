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

}
?>