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
use Jekk0\Cbrf\Client\Interfaces\CurrencyApi;
use Jekk0\Cbrf\Client\Interfaces\HttpClientApi;
use Jekk0\Cbrf\Client\Traits\DateValidator;
use Jekk0\Cbrf\Client\Traits\XmlResponseParser;

/**
 * Class Currency
 * @package Jekk0\Cbrf\Client\Entity
 */
class Currency implements CurrencyApi
{
    use XmlResponseParser, DateValidator;

    protected $httpClient = null;

    public function __construct(HttpClientApi $httpClientApi)
    {
        $this->httpClient = $httpClientApi;
    }

    /**
     * @param null $date
     *
     * @return array
     *
     * @throws \Jekk0\Cbrf\Client\Exceptions\InvalidDateFormatException
     * @throws \Jekk0\Cbrf\Client\Exceptions\InvalidRequestParamsException
     * @throws \Jekk0\Cbrf\Client\Exceptions\InvalidXmlFormatException
     */
    public function getAll($date = null)
    {
        $date = $this->setDefaultDateIfNotSet($date);
        $this->validateDate($date);
        $query = http_build_query([CbrfConstants::ALL_CURRENCIES_QUOTATIONS_DATE => $date]);
        $data = $this->httpClient->get(CbrfConstants::ALL_CURRENCIES_QUOTATIONS_URL . '?' . $query);
        $data = str_replace('"windows-1251"', '"utf-8"', $data);
        $data = $this->w1251ToUtf8($data);
        $currencies = $this->xmlToArray($data, 'Valute');

        return $this->stringValuesToFloat($currencies, ['Value']);
    }

    public function getByNumCode($numCode, $date = null)
    {
        return $this->getCurrencyBy($numCode, 'NumCode', $date);
    }

    public function getByCharCode($charCode, $date = null)
    {
        return $this->getCurrencyBy($charCode, 'CharCode', $date);
    }

    public function getById($id, $date = null)
    {
        return $this->getCurrencyBy($id, 'ID', $date);
    }

    public function getIds($date = null)
    {
        return $this->getCurrenciesColumn('ID', 'CharCode', $date);
    }


    public function getIdByCharCode($charCode, $date = null)
    {
        $currencyIds = $this->getIds($date);

        return isset($currencyIds[$charCode]) ? $currencyIds[$charCode] : false;
    }

    /**
     * @param null $date
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getDifference($date = null)
    {
        $date = $this->setDefaultDateIfNotSet($date);

        $dateTime = \DateTimeImmutable::createFromFormat(CbrfConstants::DATE_FORMAT, $date);
        $dayOfWeek = (int)$dateTime->format('w');
        switch ($dayOfWeek) {
            case 0:
                //Sunday
                $subPeriod = 'P2D';
                break;
            case 1:
                //Monday
                $subPeriod = 'P3D';
                break;
            case 6:
                //Saturday
                $subPeriod = 'P1D';
                break;
            default:
                $subPeriod = 'P0D';
                break;
        }
        $dateTime = $dateTime->sub(new \DateInterval($subPeriod));
        $previous = $dateTime->sub(new \DateInterval('P1D'));
        $currencies = $this->getCurrenciesColumn('Value', 'CharCode', $dateTime->format(CbrfConstants::DATE_FORMAT));
        $currenciesPreviousDay = $this->getCurrenciesColumn(
            'Value',
            'CharCode',
            $previous->format(CbrfConstants::DATE_FORMAT)
        );
        $difference = [];
        foreach ($currencies as $charCode => $currency) {
            $result = isset($currenciesPreviousDay[$charCode]) ? ((double)$currency
                - (double)$currenciesPreviousDay[$charCode]) : 0;
            $difference[$charCode] = number_format($result, 4, '.', '');
        }

        return $difference;
    }

    /**
     * @param $currencyId
     * @param $date1
     * @param $date2
     *
     * @return array
     * @throws \Jekk0\Cbrf\Client\Exceptions\InvalidDateFormatException
     * @throws \Jekk0\Cbrf\Client\Exceptions\InvalidRequestParamsException
     * @throws \Jekk0\Cbrf\Client\Exceptions\InvalidXmlFormatException
     */
    public function getDynamics($currencyId, $date1, $date2)
    {
        $this->validateDate($date1);
        $this->validateDate($date2);

        $query = http_build_query(
            [
                CbrfConstants::CURRENCY_DYNAMICS_QUOTATIONS_DATE1 => $date1,
                CbrfConstants::CURRENCY_DYNAMICS_QUOTATIONS_DATE2 => $date2,
                CbrfConstants::CURRENCY_DYNAMICS_QUOTATIONS_CURRENCY_ID => $currencyId
            ]
        );

        $data = $this->httpClient->get(CbrfConstants::CURRENCY_DYNAMICS_QUOTATIONS_URL . '?' . $query);

        $currencyDynamics = $this->xmlToArray($data, 'Record');

        return $this->stringValuesToFloat($currencyDynamics, ['Value']);
    }

    protected function getCurrenciesColumn($column, $key, $date = null)
    {
        $currencies = $this->getAll($date);

        return array_column($currencies, $column, $key);
    }

    protected function getCurrencyBy($key, $column, $date)
    {
        $currencies = $this->getAll($date);

        return $this->searchInArray($currencies, $key, $column);
    }
}
