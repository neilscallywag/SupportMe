<?php


$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

if ((isset($uri[2]) && $uri[2] != 'user') || !isset($uri[3])) {
    header("HTTP/1.1 404 Not Found");
    exit();
}

require "Controller/RegisterController.php";
require "Model/Database.php";

$checkDatabase = new Database();
if ($checkDatabase->isConnected) {
    echo "Database is connected";
}

?>