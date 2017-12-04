<?php
/**
 * PHP wrapper for Central Bank Of Russian Federation Api.
 *
 * @package    Jekk0/cbrf-api-helper
 * @author     Jekko https://github.com/Jekk0
 * @license    MIT
 * @link       https://github.com/Jekk0/cbrf-api-helper
 */
namespace Jekk0\Apicbrf;

/**
 * Class CurlInstance
 * @package Jekk0\Apicbrf
 */
class CurlInstance {

    protected $curl;

    protected $defaults = [
        CURLOPT_RETURNTRANSFER => TRUE
    ];

    public function __construct() {
        $this->curl = curl_init();
    }

    public function get($url, $options = array()) {
        return $this->request('GET', $url, $options);
    }

    public function post($url, $options = array()) {
        return $this->request('POST', $url, $options);
    }

    public function request($method, $url, $options) {
        $options[CURLOPT_CUSTOMREQUEST] = $method;
        $options[CURLOPT_URL] = $url;
        $options = $options + $this->defaults;
        $this->applyOptions($options);

        return curl_exec($this->curl);
    }

    protected function applyOptions($options) {
        foreach ($options as $option => $value) {
            curl_setopt($this->curl,$option, $value);
        }
    }

}