<?php

namespace Jekk0\Apicbrf;


class Apicbrf {

    protected $curl;
    
    public function __construct() {
        $this->curl = new CurlInstance();
    }

    public function getCurrency($currency) {
        
    }

    public function getAllCurrencies($date = NULL) {
        return $this->curl->get(ApicbrfConstants::ALL_CURRENCIES_QUOTATIONS_URL);
    }

}