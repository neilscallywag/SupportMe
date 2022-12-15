<?php

require 'Database.php';

$DAO = new UserDAO();
$result = $DAO->fetch_password("1");

var_dump($result)
?>