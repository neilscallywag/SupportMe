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
}
}


?>