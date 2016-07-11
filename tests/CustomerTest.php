<?php

namespace Solaris\Tests;

use Solaris\Requests\AddCustomerRequest;

class CustomerTest extends TestCase
{
    /**
     * @return \Solaris\Requests\AddCustomerRequest
     */
    protected function getRandomAddCustomerRequest()
    {
        $email = 'text'.time().'@gmail.com';
        $password = md5(rand());

        $request = new AddCustomerRequest([
            'firstName' => $this->faker->firstName,
            'lastName'  => $this->faker->lastName,
            'email'     => $email,
            'password'  => $password,
            'phone'     => $this->faker->randomNumber(8),
            'country'   => $this->faker->countryCode,
            'ip'        => $this->faker->ipv4,
        ]);

        return $request;
    }

    public function testRegister()
    {
        $request = $this->getRandomAddCustomerRequest();

        /** @var \Solaris\Responses\AddCustomerResponse $response */
        $response = $this->apiClient->addCustomer($request);

        $this->assertGreaterThan(0, $response->getId());
        $this->assertNotEmpty($response->getAuthUrl());
    }

    /**
     * @expectedException \Solaris\Exceptions\EmailAlreadyExistsException
     */
    public function testEmailAlreadyExistsException()
    {
        $request = $this->getRandomAddCustomerRequest();
        $this->apiClient->addCustomer($request);
        $this->apiClient->addCustomer($request);
    }
}