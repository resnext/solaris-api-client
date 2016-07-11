<?php

namespace Solaris\Responses;

use Solaris\Exceptions\EmailAlreadyExistsException;
use Solaris\Response;

class AddCustomerResponse extends Response
{
    protected $id;

    protected $authUrl;

    public function init()
    {
        $data = $this->payload->getData();

        if (isset($data['message']) && $data['message'] == 'E-mail already exists') {
            throw new EmailAlreadyExistsException($this, $data['message']);
        }

        $this->id = $data['id'];

        $this->authUrl = $data['authUrl'];
    }

    /**
     * Returns ID of created user from trade platform Solaris
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
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