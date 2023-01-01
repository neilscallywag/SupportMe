<?php
include_once(__DIR__ . "/BaseController.php");
include_once(__DIR__ . "/../inc/config.php");

class CommentController extends BaseController
{

    private int $campaign_id;
    private int $user_id;
    private int $comment_id;

    /**
     * This function fetches the comments based on the campaign id
     * @author Joshua
     * 
     * @param int $campaign_id
     * 
     * @return 200 with all the comments associated with the campaign
     * @return 204 if no comments are found. 
     * 
     */
    public function fetch_comments($campaign_id)
    {
        if (empty($campaign_id)) {
            $response = json_encode(array("error" => EMPTY_JSON));
            $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));
        } else {
            $this->campaign_id = $campaign_id;

            include __DIR__ . "/../Model/DAO/CommentDAO.php";
            $DAO = new CommentDAO();

            $this->sendMultipleJSON($DAO->fetch_all_comments($this->campaign_id));

        }
    }

    /**
     * This function adds a new comment to the associated campaign ID
     * @author Joshua
     * 
     * @param int $comment_id
     * @param int $user_id
     * @param string $json is the comment text.  may include reply_id
     * 
     * @return 400 if malformed request or missing params
     * @return 200 if comment successfully changed.
     * 
     */
    public function edit_comment($user_id, $comment_id, $json)
    {
        if (empty($comment_id) || empty($user_id) || empty($json)) {
            $response = json_encode(array("error" => EMPTY_JSON));
            $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));

        } else {
            $this->user_id = $user_id;
            $this->comment_id = $comment_id;
            $json = json_decode($json, true);

            if (!isset($json['comment_text'])) {
                $response = json_encode(array("error" => EMPTY_COMMENT));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));

            }
            ;

            include __DIR__ . "/../Model/DAO/CommentDAO.php";
            $DAO = new CommentDAO();

            if (!$DAO->check_comment_user($this->user_id, $this->comment_id)) {
                $response = json_encode(array("error" => MISMATCH_USER_CAMPAIGN));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));

            }
            ;

            if ($DAO->edit_comment($this->comment_id, $json['comment_text'])) {
                $response = json_encode(array("message" => "Successfully changed comment"));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
            }

        }
    }

    /**
     * This function edits an existing comment to the associated campaign ID
     * @author Joshua
     * 
     * @param int $campaign_id
     * @param int $user_id
     * @param string $json is the comment text. 
     * 
     * @return 400 if malformed request or missing params
     * @return 200 if comment successfully added.
     * 
     */
    public function add_comment($user_id, $campaign_id, $json)
    {
        if (empty($campaign_id) || empty($user_id) || empty($json)) {
            $response = json_encode(array("error" => EMPTY_JSON));
            $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));

        } else {
            $this->user_id = $user_id;
            $this->campaign_id = $campaign_id;
            $json = json_decode($json, true);

            if (!isset($json['comment_text'])) {
                $response = json_encode(array("error" => EMPTY_COMMENT));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));

            }
            ;

            include __DIR__ . "/../Model/DAO/CommentDAO.php";
            $DAO = new CommentDAO();

            if (isset($json['reply_id'])) {
                $reply_id = $json['reply_id'];
                if (!$DAO->check_comment_campaign($reply_id, $this->campaign_id)) {
                    $response = json_encode(array("error" => MISMATCH_REPLY_CAMPAIGN));
                    $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));

                }
            }
            ;

            if ($DAO->add_comment($this->user_id, $this->campaign_id, $json['comment_text'], isset($reply_id) ? $reply_id : NULL)) {
                $response = json_encode(array("message" => "Successfully added comment"));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
            }

        }
    }



    /**
     * This function deleted the comment from the campaign associated with the user
     * @author Joshua
     * 
     * @param int $campaign_id
     * @param int $user_id
     * 
     * @return 400 if malformed request or missing params
     * @return 200 if comment successfully deleted.
     * 
     */
    public function delete_comment($user_id, $comment_id)
    {
        if (empty($comment_id) || empty($user_id)) {
            $response = json_encode(array("error" => EMPTY_JSON));
            $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));

        } else {
            $this->user_id = $user_id;
            $this->comment_id = $comment_id;


            include __DIR__ . "/../Model/DAO/CommentDAO.php";
            $DAO = new CommentDAO();

            if (!$DAO->check_comment_user($this->user_id, $this->comment_id)) {
                $response = json_encode(array("error" => MISMATCH_USER_COMMENT));
                $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 400 Bad Request'));

            }


            $message = $DAO->delete_comment($this->user_id, $this->comment_id) ? "Comment deleted" : "No change : comment not found";
            $response = json_encode(array("message" => $message));
            $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));

        }
    }
}

?>