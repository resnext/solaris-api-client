<?php

namespace Solaris\Entities;

class Country
{
    protected $code;

    public function __construct($code)
    {
        $this->code = $code;
    }

    public function getCode()
    {
        return $this->code;
    }
}