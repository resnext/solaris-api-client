<?php

namespace Solaris\Responses;

use Solaris\Exceptions\UnknownEmailException;
use Solaris\Response;

class GetCustomerAuthKeyResponse extends Response
{
    protected $authUrl;

    public function init()
    {
        $data = $this->payload->getData();

        if (isset($data['message']) && $data['message'] == 'Access denied: alien customers are not available.') {
            throw new UnknownEmailException($this, $data['message']);
        }

        $this->authUrl = $data['authUrl'];
    }

    /**
     * Returns auto-login URL for created user.
     *
     * @return string
     */
    public function getAuthUrl()
    {
        return $this->authUrl;
    }
}