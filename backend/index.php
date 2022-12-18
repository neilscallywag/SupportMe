<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header('Content-Type: application/json; charset=UTF-8');

require_once __DIR__ . '\vendor\autoload.php';
include_once __DIR__ . "\inc\config.php";

spl_autoload_register(function ($class) {
    require_once __DIR__ . "/Controller/$class.php";
});

date_default_timezone_set('Asia/Singapore');

$klein = new \Klein\Klein();

//index
$klein->respond('GET', '/?', function ($request, $response) {
    return $response->headers();
});

//register
$klein->respond('POST', '/register', function ($request, $response) {
    $json = file_get_contents('php://input');
    $base = new RegisterController();
    $base->Register($json);
});

//login
$klein->respond('POST', '/login', function ($request, $response) {
    $headers = getallheaders();
    $json = file_get_contents('php://input');
    $base = new LoginController();
    $base->Login($json, $headers);
});

//fetch campaign
$klein->respond('POST', '/campaign/id/[i:cid]', function ($request, $response) {
    #Get all the headers first
    $headers = getallheaders();

    #if autherisation header exist
    if (in_array('Authorization', $headers)) {
        $authToken = $headers['Authorization'];
        $check = new AuthController();
        if ($check->CheckGivenToken($authToken)) {
            $base = new CampaignController();
            $base->GetByID($request->cid);
        }
        ;
    }

});

//search campaign
$klein->respond('POST', '/campaign/search/[*:str]', function ($request, $response) #do you want substr here or in json
{
    #Get all the headers first
    $headers = getallheaders();

    #if autherisation header exist
    if (in_array('Authorization', $headers)) {
        $authToken = $headers['Authorization'];
        $check = new AuthController();
        if ($check->CheckGivenToken($authToken)) {
            $base = new CampaignController();
            $campaign_substr = urldecode($request->str);
            $base->search_campaign($campaign_substr);
        }
        ;
    }

});

//create campaign
$klein->respond('POST', '/campaign/create', function ($request, $response) #do you want substr here or in json
{
    #Get all the headers first
    $headers = getallheaders();

    #if autherisation header exist
    if (in_array('Authorization', $headers)) {
        $authToken = $headers['Authorization'];
        $check = new AuthController();
        if ($check->CheckGivenToken($authToken)) {
            $json = file_get_contents('php://input');
            $base = new CampaignController();
            $base->createCampaign($json);
        }
        ;
    }

});

$klein->dispatch();

?>