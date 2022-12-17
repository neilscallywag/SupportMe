<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header('Content-Type: application/json; charset=UTF-8');


require_once __DIR__ . '\vendor\autoload.php';
include_once __DIR__ . "\inc\config.php";

spl_autoload_register(
    function ($class)
    {
        require_once __DIR__ . "/Controller/$class.php";
    }
);


date_default_timezone_set('Asia/Singapore');

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
    $headers = getallheaders();
    $base = new LoginController();
    $base->Login($json,$headers);
});

$klein->respond('POST', '/campaign/id/[i:cid]', function ($request, $response)
{
    #Get all the headers first 
    $headers = getallheaders();
    $json = file_get_contents('php://input');
    $check = new AuthController();
    if ($check->CheckGivenToken($headers, $json)) #update for logical consistency
    {
        $base = new CampaignController();
        $base->GetByID($request->cid);
    }
    ;


});

$klein->respond('POST', '/campaign/search/[*:str]', function ($request, $response) 
{
    #Get all the headers first 
    $headers = getallheaders();
    $json = file_get_contents('php://input');
    $check = new AuthController();
    if ($check->CheckGivenToken($headers, $json)) #update for logical consistency
    {
        $base = new CampaignController();
        $base->search_campaign($request->str);
    }
    ;
});

$klein->respond('POST', '/campaign/create', function ($request, $response) 
{
    #Get all the headers first 
    $headers = getallheaders();
    $json = file_get_contents('php://input');
    $check = new AuthController();
    if ($check->CheckGivenToken($headers, $json)) #update for logical consistency
    {
        $base = new CampaignController();
        $base->createCampaign($json);
    }
    ;
});



$klein->dispatch();

?>