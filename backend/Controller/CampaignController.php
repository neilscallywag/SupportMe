<?php
include_once(__DIR__ . "/BaseController.php");
include_once(__DIR__ . "/../inc/config.php");

class CampaignController extends BaseController
{

    private string $campaign_id;
    private string $campaign_substr;
    private string $user_id;

    /**
     * This function takes in an integer campaign id and returns the campaign associated with the campaign id.
     * @author Joshua 
     * 
     * @param int $campaign_id is identifier of the campaign
     * 
     * @return void
     * @return 200 if successfully created a new campaign
     * @return 400 if malformed parameter
     * @return 204 if the campaign not found
     */
    public function GetByID($campaign_id)
    {
        if (empty($campaign_id)) {

            $response = json_encode(array("error" => CAMPAIGN_ID_INVALID));
            $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));

        } else {
            $this->campaign_id = $campaign_id;

            include __DIR__ . "/../Model/DAO/CampaignDAO.php";
            $DAO = new CampaignDAO();
            $campaign_info = $DAO->fetch_campaign($this->campaign_id);

            if ($campaign_info) {
                $response = json_encode($campaign_info);
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
            } else {
                $response = json_encode(array("error" => CAMPAIGN_NOT_FOUND));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 204 Bad Request'));
            }
            ;

        }

    }

    /**
     * This function takes in an encoded text string and searches for a campaign based on the string
     * @author Joshua 
     * 
     * @param string $campaign_substr is the url encoded text of the campaign
     * 
     * 
     * @return void
     * @return 200 if successfully created a new campaign
     * @return 400 if malformed string
     * @return 204 if the campaign not found
     */
    public function search_campaign($campaign_substr)
    {
        if (empty($campaign_substr)) {
            $response = json_encode(array("error" => EMPTY_CAMPAIGN_SEARCH));
            $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));

        } else {
            $this->campaign_substr = $campaign_substr;

            include __DIR__ . "/../Model/DAO/CampaignDAO.php";
            $DAO = new CampaignDAO();
            $campaigns_info = $DAO->search_campaign($this->campaign_substr);

            $return_arr = array_map('json_encode', $campaigns_info);

            if (!empty($return_arr)) {
                $response = implode('\r\n', $return_arr) . '\r\n';
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
            } else {
                $response = json_encode(array("message" => "No campaign found"));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
            }
            ;
        }

    }


    /**
     * This function takes in a JSON string and creates a new campaign.
     * @author Neil 
     * 
     * @param string $data is a JSON string
     * 
     * @var int data["user_id"]                 : is the user's ID
     * @var string data["campaign_title"]       : is the campaign title. Limit 100 words
     * @var string data["campaign_description"] : is the campaign description. Limit 500 words
     * @var string data["campaign_picture"]     : is an optional image Base64 string.
     * 
     * @return void
     * @return 200 if successfully created a new campaign
     * @return 400 if missing fields or incorrect length of title or description
     * @return 500 if database error
     */

    public function createCampaign(string $data): void
    //TODO: implement length check for title and description

    {
        if (empty($data)) {
            $response = json_encode(array("error" => EMPTY_JSON));
            $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));

        } else {
            $data = json_decode($data, true);
            /*
            {
            user_id:,
            campaign_title:
            campaign_description:
            campaign_picture:
            }
            */
            if (empty($data["user_id"])) {
                $response = json_encode(array("error" => EMPTY_USER_ERROR));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));

            } elseif (empty($data["campaign_title"])) {
                $response = json_encode(array("error" => EMPTY_CTITLE_ERROR));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));

            } elseif (empty($data["campaign_description"])) {
                $response = json_encode(array("error" => EMPTY_CDESCRIPTION_ERROR));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));

            } else {
                require_once __DIR__ . '\..\Model\CampaignModel.php';
                $campaign = new Campaign();
                if ($campaign->addCampaign($data["user_id"], $data["campaign_title"], $data["campaign_description"], array_key_exists("campaign_picture", $data) ? $data["campaign_picture"] : NULL)) {
                    $output = json_encode(array("message" => CAMPAIGN_SUCCESS));
                    $this->sendOutput($output, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
                } else {
                    $output = json_encode(array("error" => CAMPAIGN_FAIL));
                    $this->sendOutput($output, array('Content-Type: application/json', 'HTTP/1.1 500'));
                }
                ;
            }
           
        }

    }

    /**
     * This function takes in a JSON string and edits a campaign if the user matches.
     * @author Joshua
     * 
     * @param string $data is a JSON string (converted inside)
     * 
     * @var int data["user_id"]                 : is the user's ID
     * @var string data["campaign_title"]       : is the campaign title. Limit 100 words
     * @var string data["campaign_description"] : is the campaign description. Limit 500 words
     * @var string data["campaign_picture"]     : is an optional image Base64 string.
     * 
     * @return void
     * @return 200 if successfully edited a new campaign
     * @return 400 if missing fields or incorrect length of title or description
     * @return 500 if database error
     */

     public function edit_campaign($user_id,$campaign_id,string $data): void
 
     {
         if (empty($data)) {
             $response = json_encode(array("error" => EMPTY_JSON));
             $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
 
         } else {
             $data = json_decode($data, true);
             $this->user_id=$user_id;
             $this->campaign_id=$campaign_id;
             $anyedit=false;

             require_once __DIR__ . '\..\Model\DAO\CampaignDAO.php';
             $DAO = new CampaignDAO();

             if ($DAO->check_user_campaign($this->user_id,$this->campaign_id)) {
            /*
             {
             user_id:,
             campaign_title:
             campaign_description:
             campaign_picture:
             }
             */
                if (isset($data['campaign_title'])){
                    $DAO->edit_campaign_title($this->campaign_id,$data['campaign_title']);
                    $anyedit=true;
                } ;
                
                if (isset($data['campaign_description'])) {
                    $DAO->edit_campaign_description($this->campaign_id,$data['campaign_description']);
                    $anyedit=true;
                };
                if (isset($data['campaign_picture'])) {
                    $DAO->edit_campaign_picture($this->campaign_id,$data['campaign_picture']);
                    $anyedit=true;
                };


                if ($anyedit){
                    $output = json_encode(array("message" => "campaign successfully edited"));
                    $this->sendOutput($output, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
                } else {
                    $output = json_encode(array("message" => "no change committed"));
                    $this->sendOutput($output, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
                }
             } else {
                $response = json_encode(array("error" => MISMATCH_USER_CAMPAIGN));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
             }


         }
 
     }

    /**
     * This function updates the database record for the given campaign
     * @author Neil 
     * 
     * @param string $campaign_id is the id of the campaign
     * @param string $data is a JSON string
     * 
     * @return void
     * @return 200 if successfully created a new campaign
     * @return 400 if missing fields or incorrect length of title or description
     * @return 500 if database error
     */

    public function updateCampaign(int $campaign_id, int $user_id, string $json): void
    {
    }

    /**
     * This function takes deletes the given campaign
     * @author Neil 
     * 
     * @param string $campaign_id is the id of the campaign
     * @param string $user_id is the id of the user
     *
     * @return void
     * @return 200 if successfully created a new campaign
     * @return 400 if missing fields or incorrect length of title or description
     * @return 500 if database error
     */

    public function deleteCampaign(int $user_id, int $campaign_id): void
    {
        if (empty($user_id) || empty($campaign_id)) {
            $response = json_encode(array("error" => EMPTY_JSON));
            $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
        } else {
            $this->user_id = $user_id;
            $this->campaign_id = $campaign_id;

            include __DIR__ . "/../Model/DAO/CampaignDAO.php";
            $DAO = new CampaignDAO();
            if ($DAO->fetch_campaign($this->campaign_id)['user_id'] == $this->user_id) {
                $DAO->delete_campaign($campaign_id);
                $response = json_encode(array("message" => "Campaign successful deleted"));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 401 Authentication Error'));
            } else {
                $response = json_encode(array("error" => MISMATCH_USER_CAMPAIGN));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 401 Authentication Error'));
            }
        }
    }


    /**
     * This function takes the given user ID and returns the list of campaigns for that user
     * @author Joshua 
     * 
     * @param string $user_id is the id of the user
     *
     * @return void
     * @return 200 if successfully returned a JSON string
     * @return 400 if missing user id
     * @return 500 if database error
     */
    public function getbyUID(int $user_id): void
    {
        if (empty($user_id)) {
            $response = json_encode(array("error" => EMPTY_JSON));
            $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
        } else {
            $this->user_id = $user_id;

            include __DIR__ . "/../Model/DAO/CampaignDAO.php";
            $DAO = new CampaignDAO();
            $this->sendMultipleJSON($DAO->fetch_user_campaign($this->user_id));

            ;
        }
    }
}

?>