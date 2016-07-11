<?php

namespace Solaris\Responses;

use Solaris\Entities\Country;
use Solaris\Response;

class GetCountriesResponse extends Response
{
    protected $countries = [];

    public function init()
    {
        $data = $this->payload->getData();

        foreach ($data['countries'] as $country) {
            $this->countries[] = new Country($country['code']);
        }
    }

    /**
     * @return \Solaris\Entities\Country[]
     */
    public function getCountries()
    {
        return $this->countries;
    }

}