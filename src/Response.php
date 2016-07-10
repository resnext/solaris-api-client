<?php

namespace Solaris;

class Response
{
    protected $payload;

    final public function __construct(Payload $payload)
    {
        $this->payload = $payload;
        $this->init();
    }

    public function init()
    {
    }
}
