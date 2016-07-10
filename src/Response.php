<?php

namespace Solaris;

class Response
{
    protected $data;

    public function __construct(Payload $payload)
    {
        $this->data = $payload;
    }

    public function isSuccess()
    {
        return false;
    }
}
