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

use Jekk0\Cbrf\Client\Entities\Currency;
use Jekk0\Cbrf\Client\Entities\PreciousMetal;
use Jekk0\Cbrf\Client\Interfaces\CurrencyApi;
use Jekk0\Cbrf\Client\Interfaces\HttpClientApi;
use Jekk0\Cbrf\Client\Interfaces\PreciousMetalApi;

/**
 * Class Client
 * @package Jekk0\Cbrf\Client
 */
class Client
{
    protected $httpClient = null;
    protected $currency = null;
    protected $preciousMetal = null;

    public function getCurrencyApi() : CurrencyApi
    {
        return $this->currency ?: new Currency($this->getHttpClient());
    }

    public function getPreciousMetalApi() : PreciousMetalApi
    {
        return $this->currency ?: new PreciousMetal($this->getHttpClient());
    }

    public function setHttpClient(HttpClientApi $httpClientApi)
    {
        $this->httpClient = $httpClientApi;
    }
    
    protected function getHttpClient() : HttpClientApi
    {
        return $this->httpClient ?: new HttpClient();
    }

}
