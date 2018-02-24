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

use Jekk0\Apicbrf\Exceptions\InvalidDateFormatException;
use Jekk0\Apicbrf\Exceptions\InvalidRequestParamsException;
use Jekk0\Apicbrf\Exceptions\InvalidXmlFormatException;

/**
 * Class Apicbrf
 * @package Jekk0\Apicbrf
 */
class Apicbrf
{

    protected $curl;

    public function __construct()
    {
        $this->curl = new CurlInstance();
    }

    public function getAllCurrencies($date = null)
    {
        $date = $this->setDefaultDateIfNotSet($date);
        $this->validateDate($date);
        $query = http_build_query(array(
            ApicbrfConstants::ALL_CURRENCIES_QUOTATIONS_DATE => $date
        ));
        $data = $this->curl->get(ApicbrfConstants::ALL_CURRENCIES_QUOTATIONS_URL . '?' . $query);
        $data = str_replace('"windows-1251"', '"utf-8"', $data);
        $data = $this->w1251ToUtf8($data);
        $currencies = $this->xmlToArray($data, 'Valute');
        return $this->stringValuesToFloat($currencies, array('Value'));
    }

    public function getCurrencyByNumCode($numCode, $date = null)
    {
        return $this->getCurrencyBy($numCode, 'NumCode', $date);
    }

    public function getCurrencyByCharCode($charCode, $date = null)
    {
        return $this->getCurrencyBy($charCode, 'CharCode', $date);
    }

    public function getCurrencyById($id, $date = null)
    {
        return $this->getCurrencyBy($id, 'ID', $date);
    }

    public function getCurrenciesIds($date = null)
    {
        return $this->getCurrenciesColumn('ID', 'CharCode', $date);
    }

    public function getCurrenciesColumn($column, $key, $date = null)
    {
        $currencies = $this->getAllCurrencies($date);
        return array_column($currencies, $column, $key);
    }

    public function getCurrenciesDifference($date = null)
    {
        $date = $this->setDefaultDateIfNotSet($date);

        $dateTime = \DateTimeImmutable::createFromFormat(ApicbrfConstants::DATE_FORMAT, $date);
        $dayOfWeek = (int)$dateTime->format('w');
        switch ($dayOfWeek) {
            case 0:
                //Sunday
                $subPeriod = 'P2D';
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
        $currencies = $this->getCurrenciesColumn('Value', 'CharCode', $dateTime->format(ApicbrfConstants::DATE_FORMAT));
        $currenciesPreviousDay = $this->getCurrenciesColumn(
            'Value', 'CharCode', $previous->format(ApicbrfConstants::DATE_FORMAT)
        );
        $difference = array();
        foreach ($currencies as $charCode => $currency) {
            $result = isset($currenciesPreviousDay[$charCode])
                ? ((double)$currency - (double)$currenciesPreviousDay[$charCode]) : 0;
            $difference[$charCode] = number_format($result, 4, '.', '');
        }
        return $difference;
    }

    public function getCurrencyDynamics($currencyId, $date1, $date2)
    {
        $this->validateDate($date1);
        $this->validateDate($date2);

        $query = http_build_query(array(
            ApicbrfConstants::CURRENCY_DYNAMICS_QUOTATIONS_DATE1 => $date1,
            ApicbrfConstants::CURRENCY_DYNAMICS_QUOTATIONS_DATE2 => $date2,
            ApicbrfConstants::CURRENCY_DYNAMICS_QUOTATIONS_CURRENCY_ID => $currencyId
        ));
        $data = $this->curl->get(ApicbrfConstants::CURRENCY_DYNAMICS_QUOTATIONS_URL . '?' . $query);

        $currencyDynamics = $this->xmlToArray($data, 'Record');
        return $this->stringValuesToFloat($currencyDynamics, array('Value'));
    }

    public function getMetalDynamics($date1, $date2)
    {
        $this->validateDate($date1);
        $this->validateDate($date2);

        $query = http_build_query(array(
            ApicbrfConstants::METAL_DYNAMICS_QUOTATIONS_DATE1 => $date1,
            ApicbrfConstants::METAL_DYNAMICS_QUOTATIONS_DATE2 => $date2
        ));

        $data = $this->curl->get(ApicbrfConstants::DYNAMICS_QUOTATIONS_METAL_URL . '?' . $query);
        $metalDynamics = $this->xmlToArray($data, 'Record');

        return $this->stringValuesToFloat($metalDynamics, array('Buy', 'Sell'));
    }

    public function getCurrencyIdByCharCode($charCode, $date = null)
    {
        $currencyIds = $this->getCurrenciesIds($date);

        return isset($currencyIds[$charCode]) ? $currencyIds[$charCode] : false;
    }

    protected function setDefaultDateIfNotSet($date)
    {
        if (!$date) {
            $date = date(ApicbrfConstants::DATE_FORMAT);
        }
        return $date;
    }

    protected function getCurrencyBy($key, $column, $date)
    {
        $currencies = $this->getAllCurrencies($date);

        return $this->searchInArray($currencies, $key, $column);
    }

    protected function searchInArray($array, $needle, $column)
    {
        $searchArray = array_column($array, $column);
        $index = array_search($needle, $searchArray);
        if (is_int($index)) {
            return $array[$index];
        }
        return array();
    }

    protected function xmlToArray($data, $key)
    {
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($data);

        if (!$xml && $errors = $this->getXmlErrors()) {
            throw new InvalidXmlFormatException("Error message(s): " . implode(', ', $errors));
        }
        $currencies = array();
        foreach ($xml->$key as $currency) {
            $currency = (array)$currency;
            $attributes = array_shift($currency);
            $currency = $currency + $attributes;
            $currencies[] = $currency;
        }
        if (empty($currencies) && $response = trim((string)$xml)) {
            throw new InvalidRequestParamsException("Invalid argument parameters, response return: $response");
        }
        return $currencies;
    }

    protected function validateDate($date)
    {
        $dateTime = \DateTime::createFromFormat(ApicbrfConstants::DATE_FORMAT, $date);
        if ($dateTime) {
            return $dateTime->format(ApicbrfConstants::DATE_FORMAT);
        }
        throw new InvalidDateFormatException("Invalid date format '$date', supported only: "
            . ApicbrfConstants::DATE_FORMAT);
    }

    protected function getXmlErrors()
    {
        $errors = libxml_get_errors();
        $messages = array();
        foreach ($errors as $error) {
            $messages[] = "Error: {$error->message}";
        }
        return $messages;
    }

    protected function w1251ToUtf8($string)
    {
        return mb_convert_encoding($string, 'utf-8', 'windows-1251');
    }

    protected function stringValuesToFloat($array, array $arrayKeys) {
        $array = array_map(function ($currency) use ($arrayKeys) {
            foreach ($arrayKeys as $arrayKey) {
                $value = str_replace(',', '.', $currency[$arrayKey]);
                $currency[$arrayKey] = (double)$value;
            }
            return $currency;
        }, $array);
        return $array;
    }
}
