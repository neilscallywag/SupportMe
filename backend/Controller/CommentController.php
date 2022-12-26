<?php
include_once(__DIR__ . "/BaseController.php");
include_once(__DIR__ . "/../inc/config.php");

class CommentController extends BaseController
{

    private int $campaign_id;
    private int $user_id;
    private int $comment_id;

    public function fetch_comments($campaign_id){
        if (empty($campaign_id)){
            $response = json_encode(array("error" => EMPTY_JSON));
            $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
        } else {
            $this->campaign_id = $campaign_id;

            include __DIR__ . "/../Model/DAO/CommentDAO.php";
            $DAO = new CommentDAO();

            $this->sendMultipleJSON($DAO->fetch_all_comments($this->campaign_id));

        }
    }

}

?>