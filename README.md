# Solaris API Client.
API Client for binary options platform Solaris.

## General API Client usage.
Your can configure used httpClient using $options param of ApiClient constructor.

### Error handling

Each method of \Solaris\ApiClient can return response object (instance of \Solaris\Response) or
throws two kind of exceptions.

1. \Solaris\ServerException Server-side exception assigned with invalid data received of impossible operation is requested.
2. \Solaris\ClientException Client-side exception means API Client cannot connect to Solaris servers or receive valid 
response with any reasons.

### Configuration and customization

Example:
```php
$httpClient = new GuzzleHttp\Client([
   GuzzleHttp\RequestOptions::CONNECT_TIMEOUT => 2,
]);

$apiClient = new \Solaris\ApiClient(<API_URL>, <API_USERNAME>, <API_PASSWORD>, [
    'httpClient' => $httpClient,
]);
```

## Get available countries list

For customer adding you need specify countryISO 3166-1 code (ex.: en, us, gb). You can get available for registration
countries list as bellow:

```php
/** @var \Solaris\Responses\GetCountriesResponse $response */
$response = $apiClient->getCountries();
/** @var \Solaris\Entities\Country[] $countries */
$countries = $response->getCountries();
```

## Add customer

Customer's adding is main method of any trade platform...

```php
$request = new \Solaris\Requests\AddCustomerRequest([
    'firstName' => 'John',
    'lastName' => 'Smith',
    'email' => 'john.smith@domain.com',
    'phone' => '123456789',
    'country' => 'cz',
    'currency' => 'USD',
    'password' => 'qwerty',
]);

/** @var \Solaris\Responses\AddCustomerResponse $response */
$response = $apiClient->addCustomer($request);
```

## Get customer auto-login URL and auth key.

It is really easy:

```php
$request = new \Solaris\Requests\GetCustomerAuthKeyRequest(['email' => 'john.smith@domain.com']);

/**
 * @var \Solaris\Responses\GetCustomerAuthKeyResponse $response
 */
$response = $apiClient->getCustomerAuthKey($request);

echo $response->getAuthUrl();
```
