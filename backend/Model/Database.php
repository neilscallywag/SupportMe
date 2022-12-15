<?php
### ONLY MODIFY THIS FILE IF NECESSARY 
### (e.g., you are using MAMP, you are using a different port number, etc) ###

class ConnectionManager
{
    public function getConnection()
    {
        // VERIFY THE VALUES BELOW
        
        $host     = 'localhost';
        $port     = '3306';
        $dbname   = 'supportme';
        $username = 'root';
        $password = '';

        $dsn = "mysql:host=$host;port=$port;dbname=$dbname";

        try{
            $pdo = new PDO($dsn, $username, $password);

            # We can now log any exceptions on Fatal error. 
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            # Disable emulation of prepared statements, use REAL prepared statements instead.
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);

            return $pdo;

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
            return false;
        }
    }
}

class UserDAO {
    public function fetch_password($user_id){

        $conn= new ConnectionManager();
        $pdo = $conn->getConnection(); #important i did not implement exception catch
    
        $sql = "SELECT pw_hash from user where user_id= :uid";
        $stmt = $pdo->prepare($sql);
        $stmt-> bindParam(':uid',$user_id,PDO::PARAM_INT);
        $stmt-> execute();

        $stmt->setFetchMode(PDO::FETCH_NUM);

        $result = $stmt->fetchAll();  #shd be one

        $stmt = null;
        $pdo = null;

        return $result[0][0];
    }

    public function fetch_name($user_id){

        $conn= new ConnectionManager();
        $pdo = $conn->getConnection(); #important i did not implement exception catch
    
        $sql = "SELECT firstname,lastname from user where user_id= :uid";
        $stmt = $pdo->prepare($sql);
        $stmt-> bindParam(':uid',$user_id,PDO::PARAM_INT);
        $stmt-> execute();

        $stmt->setFetchMode(PDO::FETCH_NUM);

        $result = $stmt->fetchAll();  #shd be one

        $stmt = null;
        $pdo = null;

        return $result[0];
    }

    public function add_user($firstname,$email,$pw_hash,$lastname=NULL){   #note the not normal ordering
#note for joshua double quotes
        $conn= new ConnectionManager();
        $pdo = $conn->getConnection(); #important i did not implement exception catch
    
        $sql = "insert into user (firstname,lastname,email,pw_hash) values (?,?,?,?)";
        $stmt = $pdo->prepare($sql);
        $stmt-> execute(array($firstname,$lastname,$email,$pw_hash));

        $rows = $stmt->rowCount(); #null how

        $stmt = null;
        $pdo = null;

        return $rows;
    }

}

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

}
?>