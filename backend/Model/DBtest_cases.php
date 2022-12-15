<pre>
<?php

spl_autoload_register(
    function($class) {
        require_once "$class.php";
    }
);

$DAO = new UserDAO();
$result = $DAO->fetch_password("1");

var_dump($result);

$result = $DAO->fetch_name("1");

var_dump($result);


$DAO = new CampaignDAO();
$result = $DAO->fetch_campaign("1");

var_dump($result);

$DAO = new PledgersDAO();
$result=$DAO->fetch_pledgers("1");
var_dump($result);


$DAO = new CommentDAO();
$result=$DAO->fetch_all_comment(1);
var_dump($result);

$DAO = new SessionDAO();
$result=$DAO->fetch_session('abcdef');
var_dump($result);


?>
</pre>