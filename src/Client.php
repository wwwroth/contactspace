<?php

namespace wwwroth\contactspace;

use GuzzleHttp\Client as GuzzleClient;

class Client
{
    const API_BASE_URL = 'https://apiv2.makecontact.space/';

    /**
     * @var GuzzleClient
     */
    protected $http;

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var array
     */
    private $config;

    /**
     * @param array $config
     * @throws \Exception
     */
    public function __construct(array $config)
    {
        if (!isset($config['api_key'])) {
            throw new \Exception('Please provide a valid contactSPACE API key.');
        }

        $this->apiKey = (string) $config['api_key'];
    }

    /**
     * @param string $function
     * @param array $parameters
     * @param string $method
     * @return mixed
     * @throws \Exception
     */
    public function __call(string $function, array $parameters)
    {
        $this->config = (array) require(
            __DIR__ . '/config/contactspace.php'
        );

        $this->http = new GuzzleClient();

        if (!$this->functionIsValid($function)) {
            throw new \Exception($function . ' is not a valid API function.');
        }

        return $this->request($function, $parameters, $parameters['method'] ?? null);
    }

    /**
     * @param $function
     * @param $args
     * @param $method
     * @return mixed
     */
    private function request($function, $args, $method)
    {
        if (!in_array($method, ['post, get, put, patch, delete'])) {
            throw new Exception($method . ' is not a valid HTTP API method.');
        }

        $request = $this->http->{$method}(self::API_BASE_URL, [
            'query' => $this->buildQuery($function, $args[0] ?? null)
        ]);

        return json_decode($request->getBody()->getContents());
    }

    /**
     * @param $function
     * @param $parameters
     * @return array
     */
    private function buildQuery($function, $parameters)
    {
        return array_merge([
            'apikey' => $this->apiKey,
            'outputtype' => 'json',
            'function' => ucfirst($function)
        ], $parameters ?? []);
    }

    /**
     * @param $function
     * @return bool
     */
    private function functionIsValid($function)
    {
        return in_array(ucfirst($function), $this->config['valid_functions']);
    }
}
