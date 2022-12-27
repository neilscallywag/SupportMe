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
    return "Please use POST method to access this API";
});

//register
$klein->respond('POST', '/register', function ($request, $response) {
    $json = file_get_contents('php://input');
    $base = new RegisterController();
    $base->Register($json);
});

//login
$klein->respond('POST', '/login', function ($request, $response) {
    $iHateKlein = $request->headers();
    $headers = $iHateKlein->all();
    $json = file_get_contents('php://input');
    $base = new LoginController();
    $base->Login($json, $headers);
});

//fetch campaign
$klein->respond('POST', '/campaign/id/[:cid]', function ($request, $response) {
    #Get all the headers first
    $headers=GetAllHeaders();  #Klein bug? doesnt catch authorisation header

    #if authorization header exist
    $check = new AuthController();
    if ($check->CheckGivenToken($headers)) {
        $base = new CampaignController();
        $base->GetByID($request->cid);
    }
    ;


});

//search campaign
$klein->respond('POST', '/campaign/search/[*:str]', function ($request, $response) {
    $headers=GetAllHeaders(); 

    #if autherisation header exist
    $check = new AuthController();
    if ($check->CheckGivenToken($headers)) {
        $base = new CampaignController();
        $campaign_substr = urldecode($request->str);
        $base->search_campaign($campaign_substr);
    };
});

//create campaign
$klein->respond('POST', '/campaign/create', function ($request, $response) 
{
    #Get all the headers first
    $headers=GetAllHeaders(); 

    #if autherisation header exist
    $check = new AuthController();
    if ($check->CheckGivenToken($headers)) {
        $json = file_get_contents('php://input');
        $base = new CampaignController();
        $base->createCampaign($json);
    }
    ;


});

$klein->respond('POST', '/campaign/delete/[i:cid]', function ($request, $response) 
{
    #Get all the headers first
    $headers=GetAllHeaders(); 

    #if autherisation header exist
    $check = new AuthController();
    if ($user_id=$check->CheckGivenToken($headers)) {

        $base = new CampaignController();
        $base->deleteCampaign($user_id,$request->cid);
    }
    ;


});

$klein->respond('POST', '/campaign/edit/[i:cid]', function ($request, $response) {
    $headers=GetAllHeaders(); 
    $check = new AuthController();
    $json = file_get_contents('php://input');

    if ($user_id=$check->CheckGivenToken($headers)) {
        $base = new CampaignController();
        $base->edit_campaign($user_id,$request->cid,$json);
    };
});

//fetch campaign by user ID
$klein->respond('POST', '/user/campaigns', function ($request, $response) {
    #Get all the headers first
    $headers=GetAllHeaders();  #Klein bug? doesnt catch authorisation header

    #if authorization header exist
    $check = new AuthController();
    if ($user_id = $check->CheckGivenToken($headers)) {
        $base = new CampaignController();
        $base->GetByUID($user_id);
    }
    ;


});

//campaign comments
$klein->respond('POST', '/campaign/comments/[i:cid]', function ($request, $response) {
    $headers=GetAllHeaders(); 
    $check = new AuthController();
    if ($check->CheckGivenToken($headers)) {
        $base = new CommentController();
        $base->fetch_comments($request->cid);
    };
});

$klein->respond('POST', '/campaign/add_comment/[i:cid]', function ($request, $response) {
    $headers=GetAllHeaders(); 
    $check = new AuthController();
    $json = file_get_contents('php://input');
    if ($user_id=$check->CheckGivenToken($headers)) {
        $base = new CommentController();
        $base->add_comment($user_id,$request->cid,$json);
    };
});

$klein->respond('POST', '/campaign/edit_comment/[i:coid]', function ($request, $response) {
    $headers=GetAllHeaders(); 
    $check = new AuthController();
    $json = file_get_contents('php://input');

    if ($user_id=$check->CheckGivenToken($headers)) {
        $base = new CommentController();
        $base->edit_comment($user_id,$request->coid,$json);
    };
});

$klein->respond('POST', '/campaign/delete_comment/[i:cid]', function ($request, $response) {
    $headers=GetAllHeaders(); 
    $check = new AuthController();
    if ($user_id=$check->CheckGivenToken($headers)) {
        $base = new CommentController();
        $base->delete_comment($user_id,$request->cid);
    };
});

$klein->respond('POST', '/campaign/pledge_count/[*:cid]', function ($request, $response) {
    $headers=GetAllHeaders(); 
    $check = new AuthController();
    if ($check->CheckGivenToken($headers)) {
        $base = new PledgeController();
        $base->count_pledge($request->cid);
    };
});

$klein->respond('POST', '/campaign/pledge_list/[*:cid]', function ($request, $response) {
    $headers=GetAllHeaders(); 
    $check = new AuthController();
    if ($check->CheckGivenToken($headers)) {
        $base = new PledgeController();
        $base->fetch_pledge($request->cid);
    };
});

$klein->respond('POST', '/campaign/pledge/[*:cid]', function ($request, $response) {
    $headers=GetAllHeaders(); 
    $check = new AuthController();
    $json = file_get_contents('php://input');
    if ($user_id=$check->CheckGivenToken($headers)) {
        $base = new PledgeController();
        $base->add_pledge($user_id,$request->cid,$json);
    };
});

$klein->respond('POST', '/campaign/unpledge/[*:cid]', function ($request, $response) {
    $headers=GetAllHeaders(); 
    $check = new AuthController();
    $json = file_get_contents('php://input');
    if ($user_id=$check->CheckGivenToken($headers)) {
        $base = new PledgeController();
        $base->delete_pledge($user_id,$request->cid);
    };
});

$klein->dispatch();


?>