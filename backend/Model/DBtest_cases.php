<pre>
<?php

require 'Database.php';

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

$result=$DAO->add_pledge(4,2);
var_dump($result)



?>
</pre>