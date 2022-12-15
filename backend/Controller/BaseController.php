<?php

/**
 * Summary of BaseController
 */
class BaseController
{

    /**
     * Segements the URL provided to the controller
     * @return array
     */
    protected function getUriSegments(): array
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = explode('/', $uri);

        return $uri;
    }

    /**
     * Returns an array of query string variables that are passed along with the incoming request
     * @return array
     */
    protected function getQueryStringParams(): array
    {
        parse_str($_SERVER['QUERY_STRING'], $query);
        return $query;
    }



    /**
     * Render JSON Response to the index page
     * @param string $data
     * @param array $httpHeaders
     * @return void
     */
    public function sendOutput(mixed $data, array $httpHeaders = array()): void
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
}

?>