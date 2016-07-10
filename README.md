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

## Add customer

Customer's adding is main method of any trade platform...

```php
$request = new \Solaris\Requests\AddCustomerRequest([
    'firstName' => 'John',
    'lastName' => 'Smith',
    'email' => 'john.smith.2@gmail.com',
    'phone' => '420775373671',
    'country' => 'cz',
    'currency' => 'USD',
]);

/**
 * @var \Solaris\Responses\AddCustomerResponse $response
 */
$response = $apiClient->addCustomer($request);
```