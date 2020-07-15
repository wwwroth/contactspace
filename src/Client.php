<?php

namespace wwwroth\contactSPACE;

use GuzzleHttp\Client as GuzzleClient;

class Client
{
    const API_BASE_URL = 'https://api.contactspace.com/';

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
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
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

        return $this->request($function, $parameters);
    }

    /**
     * @param $method
     * @param $args
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function request($method, $args)
    {
        $request = $this->http->get(self::API_BASE_URL, [
            'query' => $this->buildQuery($method, $args[0] ?? null)
        ]);

        return $request->getBody()->getContents();
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