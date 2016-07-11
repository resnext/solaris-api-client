<?php

namespace Solaris\Tests;

use Faker\Factory as FakerFactory;
use Solaris\ApiClient;

class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Solaris\ApiClient
     */
    protected $apiClient;

    /**
     * @var \Faker\Generator A Faker fake data generator.
     */
    protected $faker;

    /**
     * Sets up a test with some useful objects.
     */
    public function setUp()
    {
        $url = $username = $password = null;
        foreach (['url', 'username', 'password'] as $var) {
            $envVar = strtoupper('SOLARIS_'.strtoupper($var));
            if ($value = getenv($envVar)) {
                $$var = $value;
            } else {
                throw new \Exception("Environment variable $envVar is required");
            }
        }
        $this->apiClient = new ApiClient($url, $username, $password);
        $this->faker = FakerFactory::create();
    }
    /**
     * Free resources.
     */
    public function tearDown()
    {
    }
}