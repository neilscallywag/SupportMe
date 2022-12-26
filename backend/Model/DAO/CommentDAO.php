<?php
require_once 'Database.php';

class CommentDAO {  #text can be empty in db
    public function add_comment($commenter_id,$campaign_id,$comment_text,$reply_id=NULL ){

        $conn= new ConnectionManager();
        $pdo = $conn->getConnection(); #important i did not implement exception catch
    
        $sql = "insert into comment(commenter_id, campaign_id, comment_text,reply_id) values (?,?,?,?)";
        $stmt = $pdo->prepare($sql);
        $stmt-> execute(array($commenter_id,$campaign_id,$comment_text,$reply_id));


        $result = $stmt->rowCount(); 

        $stmt = null;
        $pdo = null;

        return $result;
    }

    public function delete_comment($commenter_id,$comment_id ){

        $conn= new ConnectionManager();
        $pdo = $conn->getConnection(); #important i did not implement exception catch
    
        $sql = "delete from comment where comment_id= :coid and commenter_id = :uid";
        $stmt = $pdo->prepare($sql);
        $stmt-> bindParam(':uid',$commenter_id,PDO::PARAM_INT);
        $stmt-> bindParam(':coid',$comment_id,PDO::PARAM_INT);
        $stmt-> execute();


        $result = $stmt->rowCount(); 

        $stmt = null;
        $pdo = null;

        return boolval($result);
    }

    public function fetch_all_comments($campaign_id){

        $conn= new ConnectionManager();
        $pdo = $conn->getConnection(); #important i did not implement exception catch
    
        $sql = "SELECT c.comment_id, c.createdat, c.commenter_id,u.firstname,u.lastname,c.comment_text, c.reply_id from comment c inner join user u on c.commenter_id=u.user_id where c.campaign_id = :cid";
        $stmt = $pdo->prepare($sql);
        $stmt-> bindParam(':cid',$campaign_id,PDO::PARAM_INT);
        $stmt-> execute();

        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $result = $stmt->fetchAll(); 

        $stmt = null;
        $pdo = null;

        return $result;
    }

    public function check_comment_campaign($comment_id, $campaign_id){

        $conn= new ConnectionManager();
        $pdo = $conn->getConnection(); #important i did not implement exception catch
    
        $sql = "SELECT 1 from comment c where c.campaign_id = :cid and c.comment_id = :coid ";
        $stmt = $pdo->prepare($sql);
        $stmt-> bindParam(':cid',$campaign_id,PDO::PARAM_INT);
        $stmt-> bindParam(':coid',$comment_id,PDO::PARAM_INT);
        $stmt-> execute();

        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $result = $stmt->fetchAll(); 

        $stmt = null;
        $pdo = null;

        return boolval($result);
    }

    public function check_comment_user($user_id, $comment_id){

        $conn= new ConnectionManager();
        $pdo = $conn->getConnection(); #important i did not implement exception catch
    
        $sql = "SELECT 1 from comment c where c.commenter_id = :uid and c.comment_id = :coid ";
        $stmt = $pdo->prepare($sql);
        $stmt-> bindParam(':uid',$user_id,PDO::PARAM_INT);
        $stmt-> bindParam(':coid',$comment_id,PDO::PARAM_INT);
        $stmt-> execute();

        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $result = $stmt->fetchAll(); 

        $stmt = null;
        $pdo = null;

        return boolval($result);
    }
};

?>