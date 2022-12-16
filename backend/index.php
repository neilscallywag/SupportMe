<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header('Content-Type: application/json; charset=UTF-8');
require_once "Controller/RegisterController.php";
require_once "Controller/LoginController.php";
require_once "Controller/BaseController.php";
require_once __DIR__ . '/vendor/autoload.php';

$klein = new \Klein\Klein();

//index
$klein->respond('GET', '/?', function ($request, $response)
{
    return $response->headers();
});


//register
$klein->respond('POST', '/register', function ($request, $response)
{
    $json = file_get_contents('php://input');
    $base = new RegisterController();
    $base->Register($json);
});

$klein->respond('POST', '/login', function ($request, $response)
{
    $json = file_get_contents('php://input');
    $base = new LoginController();
    $base->Login($json);
});



$klein->dispatch();

?>