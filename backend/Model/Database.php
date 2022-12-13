<?php

class Database
{


    # @var string, MySQL Hostname
    private string $hostname = 'localhost';

    # @var string, MySQL Database
    private string $database;

    # @var string, MySQL Username
    private string $username;

    # @var string, MySQL Password
    private string $password;

    # @object PDO, The PDO object
    private $pdo;

    # @bool, Whether connected to the database
    private $isConnected = false;



    /**
     * Summary of __construct
     * @param string $hostname
     * @param string $database
     * @param string $username
     * @param string $password
     */
    public function __construct()
    {
        $this->Connect($this->hostname, $this->database, $this->username, $this->password);
    }




    /**
     * Summary of Connect
     * @param string $hostname
     * @param string $database
     * @param string $username
     * @param string $password
     * @throws Exception 
     * @return void
     */
    private function Connect(string $hostname, string $database, string $username, string $password)
    {

        $dsn = 'mysql:dbname=' . $database . ';host=' . $hostname;
        try {
            $this->pdo = new PDO($dsn, $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

            # We can now log any exceptions on Fatal error. 
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            # Disable emulation of prepared statements, use REAL prepared statements instead.
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);

            # Connection succeeded, set the boolean to true.
            $this->isConnected = true;


        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

    }

    /**
     * Summary of Execute
     * @param string $sql
     * @param array $parameters
     * @throws Exception 
     * @return PDOStatement|bool
     */

    public function Execute(string $sql, array $parameters)
    {
        if (!$this->isConnected) {
            throw new Exception("Databse is not connected");
        }
        if (!$parameters) {
            return $this->pdo->query($sql);
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($parameters);
        return $stmt;
    }



}



?>