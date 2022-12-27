<?php
include_once(__DIR__ . "/BaseController.php");
include_once(__DIR__ . "/../inc/config.php");

class PledgeController extends BaseController
{

    private int $campaign_id;
    private int $user_id;
    /**
     * This function counts the number of pledges associated with the campaign
     * 
     * @param int campaign_id
     * 
     * @return 400 if malformed request
     * @return 200 with an integer count of the pledges 
     * 
     */
    public function count_pledge(int $campaign_id): void
    {
        if (empty($campaign_id)) {
            $response = json_encode(array("error" => EMPTY_JSON));
            $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
        } else {
            $this->campaign_id = $campaign_id;

            include __DIR__ . "/../Model/DAO/PledgersDAO.php";
            $DAO = new PledgersDAO();


            $response = json_encode(array('pledge_count' => $DAO->count_pledge($this->campaign_id)));
            $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));

        }
    }
    /**
     * This function fetches all the pledgers and their associated reasoning for pledging with createdAt timestamp
     * 
     * @param int campaign_id
     * @return 400 if malformed request 
     * @return 200 if successful
     * @return 204 if empty response
     * 
     */
    public function fetch_pledge(int $campaign_id): void
    {
        if (empty($campaign_id)) {
            $response = json_encode(array("error" => EMPTY_JSON));
            $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
        } else {
            $this->campaign_id = $campaign_id;

            include __DIR__ . "/../Model/DAO/PledgersDAO.php";
            $DAO = new PledgersDAO();


            $this->sendMultipleJSON($DAO->fetch_pledge($campaign_id));

        }
    }
    /**
     * This function adds a new pledge to the campaign
     * 
     * @param int campaign_id
     * @param int user_id     
     * @param int json is the reasoning to pledge to the campaign 
     * @return 400 if malformed request or pledge already exists for the given user
     * @return 200 if pledge sucessfully added
     * 
     */
    public function add_pledge(int $user_id, int $campaign_id, string $json): void
    {
        if (empty($campaign_id) || empty($user_id)) {
            $response = json_encode(array("error" => EMPTY_JSON));
            $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
        } else {
            if (!empty($json)) {
                $json = json_decode($json, true);
                $pledge_reason = isset($json['pledge_reason']) ? $json['pledge_reason'] : "";
            }
            $this->campaign_id = $campaign_id;
            $this->user_id = $user_id;

            include __DIR__ . "/../Model/DAO/PledgersDAO.php";
            $DAO = new PledgersDAO();

            if (!$DAO->check_pledge($user_id, $campaign_id)) {
                $message = $DAO->add_pledge($this->user_id, $this->campaign_id, $pledge_reason) ? "Pledge successfully added" : "Fail to add pledge";
                $response = json_encode(array('message' => $message));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));

            } else {
                $response = json_encode(array('error' => PLEDGE_ALREADY));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
            }


        }
    }
    /**
     * This function deletes the pledge 
     * 
     * @param int campaign_id
     * @param int user_id     
     * 
     * @return 400 if malformed request or pledge already exists for the given user
     * @return 200 if pledge sucessfully deleted or no pledge found
     * 
     */
    public function delete_pledge(int $user_id, int $campaign_id): void
    {
        if (empty($campaign_id)) {
            $response = json_encode(array("error" => EMPTY_JSON));
            $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
        } else {
            $this->campaign_id = $campaign_id;
            $this->user_id = $user_id;

            include __DIR__ . "/../Model/DAO/PledgersDAO.php";
            $DAO = new PledgersDAO();

            $message = $DAO->delete_pledge($user_id, $campaign_id) ? "Pledge deleted" : "No change : no such pledge";
            $response = json_encode(array('message' => $message));
            $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));

        }
    }
}

?>