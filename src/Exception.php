<?php

namespace Solaris;

class Exception extends \Exception
{
    /**
     * @var \Solaris\Payload
     */
    private $response = null;
    public function __construct(Payload $response, $message = '', $code = 0, \Exception $previous = null)
    {
        $exception = parent::__construct($message, $code, $previous);
        $this->response = $response;
        return $exception;
    }
    /**
     * @return \Solaris\Response
     */
    public function getResponse()
    {
        return $this->response;
    }
}