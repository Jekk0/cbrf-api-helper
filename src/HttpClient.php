<?php
/**
 * PHP wrapper for Central Bank Of Russian Federation Api.
 *
 * @package    Jekk0/cbrf-api-helper
 * @author     Jekko https://github.com/Jekk0
 * @license    MIT
 * @link       https://github.com/Jekk0/cbrf-api-helper
 */

namespace Jekk0\Cbrf\Client;

use Jekk0\Cbrf\Client\Interfaces\HttpClientApi;

/**
 * Class HttpClient
 * @package Jekk0\Cbrf\Client
 */
class HttpClient implements HttpClientApi
{

    protected $curl;

    protected $defaults = [CURLOPT_RETURNTRANSFER => true];

    public function __construct()
    {
        $this->curlInit();
    }

    public function get(string $uri, array $options = [])
    {
        return $this->request('GET', $uri, $options);
    }

    protected function request($method, $url, $options)
    {
        $options[CURLOPT_CUSTOMREQUEST] = $method;
        $options[CURLOPT_URL] = $url;
        $options = $options + $this->defaults;
        $this->applyOptions($options);

        return $this->curlExecute();
    }

    protected function applyOptions($options)
    {
        foreach ($options as $option => $value) {
            $this->curlSetOpt($option, $value);
        }
    }

    protected function curlInit()
    {
        $this->curl = curl_init();
    }

    protected function curlSetOpt($option, $value)
    {
        curl_setopt($this->curl, $option, $value);
    }

    protected function curlExecute()
    {
        return curl_exec($this->curl);
    }

}
