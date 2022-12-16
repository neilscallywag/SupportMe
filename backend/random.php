<pre>
<?php
    require_once "Model/DAO/CampaignDAO.php";

    $DAO = new CampaignDAO();
    var_dump($DAO->search_campaign('save'));

?>
</pre>