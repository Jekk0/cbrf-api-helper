<?php

namespace Jekk0\Apicbrf;


class Apicbrf {
    
    protected $curl;
    
    public function __construct() {
        $this->curl = new CurlInstance();
    }

    public function getCurrency($currency) {
        
    }

}