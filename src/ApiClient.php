<?php

namespace Solaris;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use GuzzleHttp;
use Solaris\Exceptions\ConnectException;
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
     * @param string $username
     * @param string $password
     * @param mixed  $options
     */
    public function __construct($url, $username, $password, $options = [])
    {
        $this->url = $url;
        $this->username = $username;
        $this->password = $password;

        if (isset($options['httpClient']) && $options['httpClient'] instanceof GuzzleHttp\ClientInterface) {
            $this->httpClient = $options['httpClient'];
        }
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

    /**
     * @param \Solaris\Requests\AddCustomerRequest $request
     *
     * @throws \Solaris\Exceptions\EmailAlreadyExistsException
     *
     * @return AddCustomerResponse
     */
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
            return (string) $this->getHttpClient()->get($url, [
                GuzzleHttp\RequestOptions::HEADERS => [
                    'User-Agent' => 'Solaris API Client',
                    'Accept'     => 'application/json',
                ]
            ])->getBody();
        } catch (GuzzleHttp\Exception\ConnectException $e) {

            return new ClientException($e->getMessage());
        } catch (GuzzleHttp\Exception\ServerException $e) {

            return (string) $e->getResponse()->getBody();
        }
    }

    /**
     * This method should be used instead direct access to property $httpClient
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
            'handler' => $stack,
            GuzzleHttp\RequestOptions::CONNECT_TIMEOUT => $this->options['timeout']
        ]);
        return $this->httpClient;
    }
}