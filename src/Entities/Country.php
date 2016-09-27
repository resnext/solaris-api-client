<?php

namespace Solaris\Entities;

class Country
{
    protected $code;

    public function __construct($code)
    {
        $this->code = $code;
    }

    /**
     * Returns two-letters ISO code of country in uppercase. Example: 'GB'.
     */
    public function getCode()
    {
        return $this->code;
    }
}
