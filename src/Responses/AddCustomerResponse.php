<?php

namespace Solaris\Responses;

use Solaris\Exceptions\EmailAlreadyExistsException;
use Solaris\Response;

class AddCustomerResponse extends Response
{
    public function init()
    {
        $data = $this->payload->getData();
        if (isset($data['message']) && $data['message'] == 'E-mail already exists') {
            throw new EmailAlreadyExistsException($this, $data['message']);
        }
    }
}