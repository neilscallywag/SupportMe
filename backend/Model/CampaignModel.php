<?php
include(__DIR__ . "/DAO/CampaignDAO.php");
class Campaign
{

    public function addCampaign(int $user_id, string $title, string $description, string $picture = NULL): bool
    {
        $DAO = new CampaignDAO();
        if ($DAO->add_campaign($user_id, $title, $description, $picture) == 1)
        {
            return true;
        }
        return false;
    }



}

?>