<?php

namespace Solaris;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use GuzzleHttp;
use Solaris\Requests\AddCustomerRequest;
use Solaris\Requests\GetCustomerAuthKeyRequest;
use Solaris\Responses\AddCustomerResponse;
use Solaris\Responses\GetCountriesResponse;
use Solaris\Responses\GetCustomerAuthKeyResponse;

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

    public function getCountries()
    {
        $data = [
            'MODULE'        => 'Countries',
            'COMMAND'       => 'get',
        ];

        $payload = new Payload($this->request($data));

        return new GetCountriesResponse($payload);
    }

    /**
     * @param \Solaris\Requests\AddCustomerRequest $request
     *
     * @throws \Solaris\Exceptions\EmailAlreadyExistsException
     *
     * @return \Solaris\Responses\AddCustomerResponse
     */
    public function addCustomer(AddCustomerRequest $request)
    {
        $data = [
            'MODULE'        => 'Customer',
            'COMMAND'       => 'add',
            'FirstName'     => $request->getFirstName(),
            'LastName'      => $request->getLastName(),
            'email'         => $request->getEmail(),
            'password'      => $request->getPassword(),
            'Phone'         => $request->getPhone(),
            'country'       => $request->getCountry(),
            'currency'      => $request->getCurrency(),
            'registered_ip' => $request->getIp(),
        ];

        $payload = new Payload($this->request($data));

        return new AddCustomerResponse($payload);
    }

    /**
     * @param \Solaris\Requests\GetCustomerAuthKeyRequest $request
     *
     * @return \Solaris\Responses\GetCustomerAuthKeyResponse
     */
    public function getCustomerAuthKey(GetCustomerAuthKeyRequest $request)
    {
        $data = [
            'MODULE'        => 'Customer',
            'COMMAND'       => 'getAuthKey',
            'email'         => $request->getEmail(),
        ];

        $payload = new Payload($this->request($data));

        return new GetCustomerAuthKeyResponse($payload);
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
        } catch (GuzzleHttp\Exception\ClientException $e) {

            return (string) $e->getResponse()->getBody();
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
        ]);
        return $this->httpClient;
    }
}