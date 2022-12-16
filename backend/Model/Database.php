<?php
### ONLY MODIFY THIS FILE IF NECESSARY 
### (e.g., you are using MAMP, you are using a different port number, etc) ###

class ConnectionManager
{
    public function getConnection()
    {
        // VERIFY THE VALUES BELOW

        $host = 'localhost';
        $port = '3306';
        $dbname = 'supportme';
        $username = 'root';
        $password = '';

        $dsn = "mysql:host=$host;port=$port;dbname=$dbname";

        try
        {
            $pdo = new PDO($dsn, $username, $password);

            # We can now log any exceptions on Fatal error. 
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            # Disable emulation of prepared statements, use REAL prepared statements instead.
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);

            return $pdo;

        }
        catch (Exception $e)
        {
            throw new Exception($e->getMessage());

        }
    }
}

?>