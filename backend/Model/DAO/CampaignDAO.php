<?php
require_once 'Database.php';

class CampaignDAO {
    public function fetch_campaign($campaign_id){

        $conn= new ConnectionManager();
        $pdo = $conn->getConnection(); #important i did not implement exception catch
    
        $sql = "SELECT starter_id as user_id,c_title,c_description,c_picture from campaign where campaign_id= :cid";
        $stmt = $pdo->prepare($sql);
        $stmt-> bindParam(':cid',$campaign_id,PDO::PARAM_INT);
        $stmt-> execute();

        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $result = $stmt->fetchAll();  #shd be one

        $stmt = null;
        $pdo = null;

        return boolval($result) ? $result[0] : false;
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

    public function delete_campaign($campaign_id){   
        $conn= new ConnectionManager();
        $pdo = $conn->getConnection(); #important i did not implement exception catch
    
        $sql = "delete from campaign where campaign_id = :cid";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':cid',$campaign_id,PDO::PARAM_INT);
        $stmt-> execute();

        $rows = $stmt->rowCount();

        $stmt = null;
        $pdo = null;

        return boolval($rows);
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

    public function check_user_campaign($user_id,$campaign_id){

        $conn= new ConnectionManager();
        $pdo = $conn->getConnection(); #important i did not implement exception catch
    
        $sql = "SELECT 1 from campaign where starter_id= :uid and campaign_id= :cid";
        $stmt = $pdo->prepare($sql);
        $stmt-> bindParam(':uid',$user_id,PDO::PARAM_INT);
        $stmt-> bindParam(':cid',$campaign_id,PDO::PARAM_INT);
        $stmt-> execute();

        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $result = $stmt->fetchAll();  #shd be one

        $stmt = null;
        $pdo = null;

        return boolval($result);
    }

    public function edit_campaign_picture($campaign_id,$campaign_picture){

        $conn= new ConnectionManager();
        $pdo = $conn->getConnection(); #important i did not implement exception catch
    
        $sql = "update campaign set c_picture= :cpict where campaign_id= :cid";
        $stmt = $pdo->prepare($sql);
        $stmt-> bindParam(':cpict',$campaign_picture,PDO::PARAM_STR);
        $stmt-> bindParam(':cid',$campaign_id,PDO::PARAM_INT);
        $stmt-> execute();

        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $rows = $stmt->rowCount(); 

        $stmt = null;
        $pdo = null;

        return boolval($rows);
    }

    public function edit_campaign_description($campaign_id,$campaign_description){

        $conn= new ConnectionManager();
        $pdo = $conn->getConnection(); #important i did not implement exception catch
    
        $sql = "update campaign set c_description= :cdescription where campaign_id= :cid";
        $stmt = $pdo->prepare($sql);
        $stmt-> bindParam(':cdescription',$campaign_description,PDO::PARAM_STR);
        $stmt-> bindParam(':cid',$campaign_id,PDO::PARAM_INT);
        $stmt-> execute();

        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $rows = $stmt->rowCount(); 

        $stmt = null;
        $pdo = null;

        return boolval($rows);
    }

    public function edit_campaign_title($campaign_id,$campaign_title){

        $conn= new ConnectionManager();
        $pdo = $conn->getConnection(); #important i did not implement exception catch
    
        $sql = "update campaign set c_title= :ctitle where campaign_id= :cid";
        $stmt = $pdo->prepare($sql);
        $stmt-> bindParam(':ctitle',$campaign_title,PDO::PARAM_STR);
        $stmt-> bindParam(':cid',$campaign_id,PDO::PARAM_INT);
        $stmt-> execute();

        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $rows = $stmt->rowCount(); 

        $stmt = null;
        $pdo = null;

        return boolval($rows);
    }
}
?>