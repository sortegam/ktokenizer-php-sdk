<?php

namespace KTokenizer;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

define('DEFAULT_KTOKENIZER_API_HOST_PRODUCTION', 'secure.ktokenizer.com');
define('DEFAULT_KTOKENIZER_API_HOST_STAGING', 'secure-stg.ktokenizer.com');
define('DEFAULT_KTOKENIZER_API_VERSION', 'v1');
define('DEFAULT_KTOKENIZER_API_IS_HTTPS', true);
define('DEFAULT_KTOKENIZER_MODE', KTokenizerClientModes::SANDBOX);

class KTokenizerClient
{

    /**
     * @var Client $http_client
     */
    private $_http_client;

    /**
     * @var string API URL (Used as a final API URL)
     */
    private $_apiUrl;

    /**
     * @var string API Base URL (Used to compose final API URL)
     */
    private $_apiHost = DEFAULT_KTOKENIZER_API_HOST_PRODUCTION;

    /**
     * @var string API ApiKey (Used as a final API URL)
     */
    private $_apiVersion = DEFAULT_KTOKENIZER_API_VERSION;

    /**
     * @var string API ApiKey (Used as a final API URL)
     */
    private $_apiIsHttps = DEFAULT_KTOKENIZER_API_IS_HTTPS;

    /**
     * @var string API ApiKey
     */
    private $_apiKey;

    /**
     * @var string Extra Guzzle Requests Options
     */
    private $extraGuzzleRequestsOptions;

    /**
     * @var KTokenizer TokenService Module
     */
    public $tokenizer;

    /**
     * KTokenizerClient constructor.
     *
     * @param string $apiKey ApiKey.
     * @param string $mode Live or sandbox.
     * @param array $extraGuzzleRequestsOptions Extra Guzzle Parameters.
     */
    public function __construct($apiKey, $mode = DEFAULT_KTOKENIZER_MODE, $extraGuzzleRequestsOptions = [])
    {
        // Set the mode / live or sandbox
        if ($mode === KTokenizerClientModes::LIVE)
        {
            $this->setApiHost(DEFAULT_KTOKENIZER_API_HOST_PRODUCTION);
        }
        else
        {
            $this->setApiHost(DEFAULT_KTOKENIZER_API_HOST_STAGING);
        }
        // Force again to set ApiURL
        $this->_setApiUrl($this->_generateApiUrl());
        $this->_setDefaultClient();
        $this->_apiKey = $apiKey;
        $this->extraGuzzleRequestsOptions = $extraGuzzleRequestsOptions;
    }

    // ---------------------------------------------------------------------------
    // PRIVATE METHODS
    // ---------------------------------------------------------------------------

    private function _generateApiUrl()
    {
        $protocol = $this->_apiIsHttps ? 'https://' : 'http://';
        return $protocol . $this->_apiHost . '/' . $this->_apiVersion . '/';
    }

    private function _setDefaultClient()
    {
        $this->_http_client = new Client();
    }

    /**
     * Method to set the API url
     * @param string $apiUrl new Api Url.
     */
    private function _setApiUrl($apiUrl)
    {
        $this->_apiUrl = $apiUrl;
    }

    // ---------------------------------------------------------------------------
    // PUBLIC METHODS
    // ---------------------------------------------------------------------------

    /**
     * Method to set Api host for the requests
     * @param string $apiHost  Api Host (example: secure-stg.ktokenizer.com).
     */
    public function setApiHost($apiHost = DEFAULT_KTOKENIZER_API_HOST)
    {
        $this->_apiHost = $apiHost;
        $this->_setApiUrl($this->_generateApiUrl());
    }

    /**
     * Method to enable or disable HTTPS on Api Endpoint
     * @param boolean $isHttpsApi Flag.
     */
    public function setApiIsHttps($isHttpsApi = true)
    {
        $this->_apiIsHttps = $isHttpsApi;
        $this->_setApiUrl($this->_generateApiUrl());
    }

    /**
     * Method needed to override the API version
     * @param string $version new Api Version.
     */
    public function setApiVersion($version = 'v1')
    {
        $this->_apiVersion = $version;
        $this->_setApiUrl($this->_generateApiUrl());
    }

    /**
     * Sets GuzzleHttp client.
     *
     * @param Client $client
     */
    public function setClient($client)
    {
        $this->_http_client = $client;
    }

    /**
     * Sends POST request to KTokenizer API.
     *
     * @param  string $endpoint
     * @param  string $json
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function post($endpoint, $json)
    {
        $guzzleRequestOptions = $this->getGuzzleRequestOptions(
            [
            'json' => $json,
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->_apiKey,
            ],
            ]
        );
        $response = $this->_http_client->request('POST', $this->_apiUrl . $endpoint, $guzzleRequestOptions);
        return $this->handleResponse($response);
    }

    /**
     * Sends PUT request to KTokenizer API.
     *
     * @param  string $endpoint
     * @param  string $json
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function put($endpoint, $json)
    {
        $guzzleRequestOptions = $this->getGuzzleRequestOptions(
            [
                'json' => $json,
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->_apiKey,
                ],
            ]
        );

        $response = $this->_http_client->request('PUT', $this->_apiUrl . $endpoint, $guzzleRequestOptions);
        return $this->handleResponse($response);
    }

    /**
     * Sends DELETE request to KTokenizer API.
     *
     * @param  string $endpoint
     * @param  string $json
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function delete($endpoint, $json)
    {
        $guzzleRequestOptions = $this->getGuzzleRequestOptions(
            [
                'json' => $json,
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->_apiKey,
                ],
            ]
        );

        $response = $this->_http_client->request('DELETE', $this->_apiUrl . $endpoint, $guzzleRequestOptions);
        return $this->handleResponse($response);
    }

    /**
     * @param string $endpoint
     * @param array  $query
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get($endpoint, $query)
    {
        $guzzleRequestOptions = $this->getGuzzleRequestOptions(
            [
                'query' => $query,
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->_apiKey,
                ],
            ]
        );

        $response = $this->_http_client->request('GET', $this->_apiUrl . $endpoint, $guzzleRequestOptions);
        return $this->handleResponse($response);
    }

    /**
     * Returns Guzzle Requests Options Array
     *
     * @param  array $defaultGuzzleRequestsOptions
     * @return array
     */
    public function getGuzzleRequestOptions($defaultGuzzleRequestOptions = [])
    {
        return array_replace_recursive($this->extraGuzzleRequestsOptions, $defaultGuzzleRequestOptions);
    }

    /**
     * @param Response $response
     * @return mixed
     */
    private function handleResponse(Response $response)
    {
        $stream = \GuzzleHttp\Psr7\stream_for($response->getBody());
        $data = json_decode($stream);
        return $data;
    }
}
