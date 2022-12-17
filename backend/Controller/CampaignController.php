<?php
include_once(__DIR__ . "/BaseController.php");
include_once(__DIR__ . "/../inc/config.php");

class CampaignController extends BaseController
{

    private string $campaign_id;
    private string $campaign_substr;

    public function GetByID($campaign_id)
    {
        if (empty($campaign_id))
        {
            $response = json_encode(array("error" => CAMPAIGN_ID_INVALID));
            $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));

        }
        else
        {
            $this->campaign_id = $campaign_id;

            include __DIR__ . "/../Model/DAO/CampaignDAO.php";
            $DAO = new CampaignDAO();
            $campaign_info = $DAO->fetch_campaign($this->campaign_id);

            if (count($campaign_info) == 1)
            {
                $response = json_encode($campaign_info[0]);
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
            }
            else
            {
                $response = json_encode(array("error" => CAMPAIGN_ID_INVALID));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
            }
            ;


        }

    }

    public function search_campaign($campaign_substr)
    {
        if (empty($campaign_substr))
        {
            $response = json_encode(array("error" => EMPTY_CAMPAIGN_SEARCH));
            $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));

        }
        else
        {
            $this->campaign_substr = $campaign_substr;

            include __DIR__ . "/../Model/DAO/CampaignDAO.php";
            $DAO = new CampaignDAO();
            $campaigns_info = $DAO->search_campaign($this->campaign_substr);

            $return_arr = array_map('json_encode', $campaigns_info);

            $response = implode('\r\n', $return_arr) . '\r\n';
            $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));

        }

    }


    public function createCampaign(string $data)
    {
        if (empty($data))
        {
            $response = json_encode(array("error" => EMPTY_JSON));
            $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));

        }
        else
        {
            $data = json_decode($data);
            /*
            {
            user_id:,
            campaign_title:
            campaign_description:
            campaign_picture:
            }
            */
            if (empty($data["user_id"]))
            {
                $response = json_encode(array("error" => EMPTY_USER_ERROR));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));

            }
            elseif (empty($data["campaign_title"]))
            {
                $response = json_encode(array("error" => EMPTY_CTITLE_ERROR));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));

            }
            elseif (empty($data["campaign_description"]))
            {
                $response = json_encode(array("error" => EMPTY_CDESCRIPTION_ERROR));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));

            }
            else
            {
                require_once __DIR__ . '\..\Model\CampaignModel.php';
                $campaign = new Campaign();
                $campaign->addCampaign($data["user_id"], $data["campaign_title"], $data["campaign_description"], $data["campaign_picture"]);
                $output = json_encode(array("message" => CAMPAIGN_SUCCESS));
                $this->sendOutput($output, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
            }
            $output = json_encode(array("message" => CAMPAIGN_FAIL));
            $this->sendOutput($output, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
        }

    }
}

?>