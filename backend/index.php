<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header('Content-Type: application/json; charset=UTF-8');
require_once "Controller/RegisterController.php";


$base = new RegisterController();


if ($_SERVER["REQUEST_METHOD"] != "POST") {

    $base->sendOutput("Invalid Request", array("HTTP/1.1 200 OK"));
} else {
    $json = file_get_contents('php://input');

    try {
        $array = $json;
        $base->Register($json);
    } catch (\JsonException $exception) {

        $response = json_encode(array("error" => $exception));
        $base->sendOutput(
            $response,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );

    }


}











/*
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);
if ((isset($uri[2]) && $uri[2] != 'user') || !isset($uri[3])) {
header("HTTP/1.1 404 Not Found");
exit();
}
require "Model/Database.php";
$checkDatabase = new Database();
if ($checkDatabase->isConnected) {
echo "Database is connected";
}
*/
?>