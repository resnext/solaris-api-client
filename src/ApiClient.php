<?php

namespace Solaris;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use GuzzleHttp;
use Solaris\Requests\AddCustomerRequest;
use Solaris\Responses\AddCustomerResponse;

class ApiClient implements LoggerAwareInterface
{
    /** @var string Solaris API endpoint URL */
    protected $url;

    /** @var string Solaris API affiliate username */
    protected $username;

    /** @var string Solaris API affiliate password */
    protected $password;

    /**
     * @var \GuzzleHttp\ClientInterface A Guzzle HTTP client.
     */
    protected $httpClient;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger = null;

    /**
     * ApiClient constructor.
     *
     * @param string $url Solaris API endpoint.
     */
    public function __construct($url, $username, $password)
    {
        $this->url = $url;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @param LoggerInterface $logger
     *
     * @return null
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function addCustomer(AddCustomerRequest $request)
    {
        $data = [
            'MODULE'    => 'Customer',
            'COMMAND'   => 'add',
            'FirstName' => $request->getFirstName(),
            'LastName'  => $request->getLastName(),
            'email'     => $request->getEmail(),
            'Phone'     => $request->getPhone(),
            'country'   => $request->getCountry(),
            'currency'  => $request->getCurrency(),
        ];

        $payload = new Payload($this->request($data));

        return new AddCustomerResponse($payload);
    }

    protected function sign(&$data)
    {
        $data['api_username'] = $this->username;
        $data['api_password'] = $this->password;
    }

    /**
     * Sends request to Solaris API endpoint.
     *
     * @param array  $data
     *
     * @return string
     */
    protected function request($data = [])
    {
        $url = rtrim($this->url, '?');

        $this->sign($data);

        $url .= '?'.http_build_query($data);

        try {

            return (string) $this->getHttpClient()->get($url)->getBody();
        } catch (GuzzleHttp\Exception\ServerException $exception) {

            return (string) $exception->getResponse()->getBody();
        }
    }

    /**
     * This method should be used insted direct access to property $httpClient
     *
     * @return \GuzzleHttp\ClientInterface|GuzzleHttp\Client
     */
    protected function getHttpClient()
    {
        if (!is_null($this->httpClient)) {
            return $this->httpClient;
        }
        $stack = GuzzleHttp\HandlerStack::create();
        if ($this->logger instanceof LoggerInterface) {
            $stack->push(GuzzleHttp\Middleware::log(
                $this->logger,
                new GuzzleHttp\MessageFormatter(GuzzleHttp\MessageFormatter::DEBUG)
            ));
        }
        $this->httpClient = new GuzzleHttp\Client([
            'handler'  => $stack,
        ]);
        return $this->httpClient;
    }
}