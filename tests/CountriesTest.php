<?php

namespace Solaris\Tests;

class CountriesTest extends TestCase
{
    public function testCountriesRetrieving()
    {
        $response = $this->apiClient->getCountries();
        $countries = $response->getCountries();
        $this->assertNotEmpty($countries);
        foreach ($countries as $country) {
            $this->assertEquals(2, strlen($country->getCode()));
        }
    }
}