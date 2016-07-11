<?php

namespace Solaris\Requests;

use Solaris\Request;

class GetCustomerAuthKeyRequest extends Request
{
    protected $email;

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
}
