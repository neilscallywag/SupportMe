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

    public function add_comment($user_id,$campaign_id,$json){
        if (empty($campaign_id) || empty ($user_id)  || empty($json)){
            $response = json_encode(array("error" => EMPTY_JSON));
            $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
            return false;
        } else {
            $this->user_id = $user_id;
            $this->campaign_id = $campaign_id;
            $json = json_decode($json, true);

            if (!isset($json['comment_text'])){
                $response = json_encode(array("error" => EMPTY_COMMENT));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
                return false;
            };

            include __DIR__ . "/../Model/DAO/CommentDAO.php";
            $DAO = new CommentDAO();

            if (isset($json['reply_id'])){
                $reply_id = $json['reply_id'];
                if (!$DAO->check_comment_campaign($reply_id,$this->campaign_id)){
                    $response = json_encode(array("error" => MISMATCH_REPLY_CAMPAIGN));
                    $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
                    return false;
                }
            };

            if ($DAO->add_comment($this->user_id,$this->campaign_id,$json['comment_text'],isset($reply_id) ? $reply_id : NULL)){
                $response = json_encode(array("message" => "Successfully added comment"));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
                }

        }
    }

    public function delete_comment($user_id,$comment_id){
        if (empty($comment_id) || empty ($user_id) ){
            $response = json_encode(array("error" => EMPTY_JSON));
            $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
            return false;
        } else {
            $this->user_id = $user_id;
            $this->comment_id = $comment_id;


            include __DIR__ . "/../Model/DAO/CommentDAO.php";
            $DAO = new CommentDAO();

            if (!$DAO->check_comment_user($this->user_id,$this->comment_id)){
                $response = json_encode(array("error" => MISMATCH_USER_COMMENT));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
                return false;
            }
            

            $message = $DAO->delete_comment($this->user_id,$this->comment_id) ? "Comment deleted" : "No change : comment not found";
            $response = json_encode(array("message" => $message));
            $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));

        }
    }
}

?>