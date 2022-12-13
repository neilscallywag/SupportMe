<?php

/**
 * Summary of BaseController
 */
class BaseController
{
    /**
     * Summary of __call
     * @param mixed $name
     * @param mixed $arguments
     * @return void
     */
    public function __call($name, $arguments)
    {
        $this->sendOutput('', array('HTTP/1.1 404 Not Found'));
    }


    /**
     * Summary of sendOutput
     * @param mixed $data
     * @param mixed $httpHeaders
     * @return never
     */
    protected function sendOutput($data, $httpHeaders = array())
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