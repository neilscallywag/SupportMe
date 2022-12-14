<?php

/**
 * Summary of BaseController
 */
class BaseController
{

    public function index()
    {
        $responseData = '{"Hello": "World"}';
        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
    }
    /**
     * Summary of __call
     * @param mixed $name
     * @param mixed $arguments
     * @return void
     */
    #public function __call(mixed $name, mixed $arguments): void
    #{
    #    $this->sendOutput('', array('HTTP/1.1 404 Not Found'));
    #}

    /**
     * Summary of getUriSegments - Get URI segments and store them in an array
     * @return array<string>|bool
     */
    protected function getUriSegments(): array
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = explode('/', $uri);

        return $uri;
    }

    /**
     * Summary of getQueryStringParams : returns an array of query string variables that are passed along with the incoming request
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
    public function sendOutput(string $data, array $httpHeaders = array()): void
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