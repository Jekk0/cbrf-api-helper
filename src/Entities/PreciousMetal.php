<?php
/**
 * PHP wrapper for Central Bank Of Russian Federation Api.
 *
 * @package    Jekk0/cbrf-api-helper
 * @author     Jekko https://github.com/Jekk0
 * @license    MIT
 * @link       https://github.com/Jekk0/cbrf-api-helper
 */

namespace Jekk0\Cbrf\Client\Entities;

use Jekk0\Cbrf\Client\Interfaces\CbrfConstants;
use Jekk0\Cbrf\Client\Interfaces\HttpClientApi;
use Jekk0\Cbrf\Client\Interfaces\PreciousMetalApi;
use Jekk0\Cbrf\Client\Traits\DateValidator;
use Jekk0\Cbrf\Client\Traits\XmlResponseParser;

/**
 * Class Currency
 * @package Jekk0\Cbrf\Client\Entity
 */
class PreciousMetal implements PreciousMetalApi
{
    use XmlResponseParser, DateValidator;

    protected $httpClient = null;

    public function __construct(HttpClientApi $httpClientApi)
    {
        $this->httpClient = $httpClientApi;
    }

    public function getDynamics($date1, $date2)
    {
        $this->validateDate($date1);
        $this->validateDate($date2);

        $query = http_build_query(
            [
                CbrfConstants::METAL_DYNAMICS_QUOTATIONS_DATE1 => $date1,
                CbrfConstants::METAL_DYNAMICS_QUOTATIONS_DATE2 => $date2
            ]
        );

        $data = $this->httpClient->get(CbrfConstants::DYNAMICS_QUOTATIONS_METAL_URL . '?' . $query);
        $metalDynamics = $this->xmlToArray($data, 'Record');

        return $this->stringValuesToFloat($metalDynamics, ['Buy', 'Sell']);
    }
}
