<?php

$test = false;
var_dump($test ===false);

require 'Database.php';
$test=new Database();
echo ($test->evaluate_join(['comment','user','campaign']));
?>