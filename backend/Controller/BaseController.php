<?php

class BaseController
{

    /**
     * Segements the URL provided to the controller
     * @author Neil 
     * @return array of the uri segments
     */
    protected function getUriSegments(): array
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = explode('/', $uri);

        return $uri;
    }

    /**
     * Returns an array of query string variables that are passed along with the incoming request
     * @author Neil 
     * @return array
     */
    protected function getQueryStringParams(): array
    {
        parse_str($_SERVER['QUERY_STRING'], $query);
        return $query;
    }



    /**
     * Render JSON Response to the index page. Is equivalent to response from request,response. 
     * @author Neil 
     * @param string $data
     * @param array $httpHeaders
     * @return void
     */
    public function sendOutput($data, array $httpHeaders = array()): void
    {
        header_remove('Set-Cookie');
        if (is_array($httpHeaders) && count($httpHeaders)) {
            foreach ($httpHeaders as $httpHeader) {
                # example:  header("HTTP/1.1 404 Not Found");
                header($httpHeader);
            }
        }

        echo $data;
        exit;
    }

    /**
     * This function returns multiple jsons 
     * @author Joshua
     * 
     * @param array $return_arr contains jsons to return
     * 
     * @return 201 if successfully send the json
     * @return 204 if no response
     * 
     */
    public function sendMultipleJSON($return_arr): void
    {
        $return_arr = array_map('json_encode', $return_arr);

        if (!empty($return_arr)) {
            $response = implode("\r\n", $return_arr) . "\r\n";
            $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
        } else {
            $response = json_encode(array("message" => "No result found"));
            $this->sendOutput($response, array('Content-Type: application/json', 'HTTP/1.1 204 OK'));
        }
    }
}

?>