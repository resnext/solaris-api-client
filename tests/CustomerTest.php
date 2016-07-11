<?php

namespace Solaris\Tests;

use Solaris\Requests\AddCustomerRequest;
use Solaris\Requests\GetCustomerAuthKeyRequest;
use Solaris\Responses\GetCustomerAuthKeyResponse;

class CustomerTest extends TestCase
{
    protected function getRandomCountry()
    {
        $response = $this->apiClient->getCountries();
        $countries = $response->getCountries();
        shuffle($countries);
        return array_pop($countries);
    }

    /**
     * @return \Solaris\Requests\AddCustomerRequest
     */
    protected function getRandomAddCustomerRequest()
    {
        $email = 'text'.time().'@gmail.com';
        $password = md5(rand());
        $country = $this->getRandomCountry();

        $request = new AddCustomerRequest([
            'firstName' => $this->faker->firstName,
            'lastName'  => $this->faker->lastName,
            'email'     => $email,
            'password'  => $password,
            'phone'     => $this->faker->randomNumber(8),
            'country'   => $country->getCode(),
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

    public function testGetCustomerAuthKeyWithValidEmail()
    {
        $request = $this->getRandomAddCustomerRequest();
        $this->apiClient->addCustomer($request);
        $request = new GetCustomerAuthKeyRequest(['email' => $request->getEmail()]);
        $response = $this->apiClient->getCustomerAuthKey($request);
        $this->assertNotEmpty($response->getAuthUrl());
    }

    /**
     * @expectedException \Solaris\Exceptions\UnknownEmailException
     */
    public function testGetCustomerAuthKeyWithWrongEmail()
    {
        $request = new GetCustomerAuthKeyRequest(['email' => $this->faker->email]);
        $this->apiClient->getCustomerAuthKey($request);
    }
}