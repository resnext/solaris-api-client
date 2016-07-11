<?php

require 'vendor/autoload.php';

$apiClient = new \Solaris\ApiClient("http://eclipse.algotrading.systems/affiliate/api", "4332f3241", "de44df96c");

/**
 * @var \Solaris\Responses\GetCountriesResponse $response
 */
$response = $apiClient->getCountries();

var_dump($response);

