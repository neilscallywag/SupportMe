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

    public function fetch_all_comment($campaign_id){

        $conn= new ConnectionManager();
        $pdo = $conn->getConnection(); #important i did not implement exception catch
    
        $sql = "SELECT c.commenter_id,u.firstname,u.lastname,c.comment_text from comment c inner join user u on c.commenter_id=u.user_id where c.campaign_id = :cid";
        $stmt = $pdo->prepare($sql);
        $stmt-> bindParam(':cid',$campaign_id,PDO::PARAM_INT);
        $stmt-> execute();

        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $result = $stmt->fetchAll(); 

        $stmt = null;
        $pdo = null;

        return $result;
    }
};

?>