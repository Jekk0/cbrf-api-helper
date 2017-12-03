<?php

namespace Jekk0\Apicbrf;

class CurlInstance {

    protected $curl;

    protected $defaults = [

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
        $this->applyOptions($options);
        
        return curl_exec($this->curl);
    }

    protected function applyOptions($options) {
        foreach ($options as $option => $value) {
            curl_setopt($this->curl,$option, $value);
        }
    }

}