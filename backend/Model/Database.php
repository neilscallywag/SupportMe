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

class Database
{

    # @object PDO, The PDO object
    private $pdo;
    # @bool, Whether connected to the database
    public $isConnected = false;

    #hardcode primary keys and foreign keys
    #does not accommodate unary comment
    private $relationship = [
        'sessionuser' => 'session.user_id=user.user_id',
        'campaignuser' => 'campaign.starter_id=user.user_id',
        'pledgers' => 'pledgers.pledger_id=user.user_id and campaign.campaign_id=pledgers.campaign_id', #pledgers is assoc - must be obtained specifically
        'campaigncommentuser'=> 'comment.commenter_id=user.user_id and comment.campaign_id=campaign.campaign_id'
    ];

    private $entity =['user','session','comment','session'];

    public function evaluate_join(array $tables) { 

        sort($tables);
        $tables_str = implode($tables);
        foreach ($this->relationship as $tables_str_valid =>  $where_clause){
            if ($tables_str==$tables_str_valid){
                return $where_clause;
            };
        };
        return in_array($tables_str,$this->entity) ? "" : false;
    }

    private function Connect()  #return false/true
    {   
        $conn = new ConnectionManager();
        return boolval($this->pdo=$conn->getConnection());
    }

# CRUD OPERATIONS : CREATE READ UPDATE DELETE

#FIRST ONE : READ (always include id)
#WHERE CLAUSE ACCEPTS where strings in an arr (cause then how if need more than less than)
#where must 

    private $general_sql_fetch = "SELECT * FROM <table_name> WHERE <where_clause>";

    public function fetch(array $tables, $where_clause=[]){
        $sql_to_prepare = $this->general_sql_fetch;

        #step 1 : putting in table name
        $table_str = implode(",",$tables);
        str_replace("<table_name>",$table_str,$sql_to_prepare);

        $where_str = $this->evaluate_join($tables);

        if ($where_str===False) {
            return False; #invalid table name
        };

        #strep 2 : assembling where



        
    }
}



?>