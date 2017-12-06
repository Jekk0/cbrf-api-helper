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
class Apicbrf {

    protected $curl;

    public function __construct() {
        $this->curl = new CurlInstance();
    }

    public function getAllCurrencies($date = NULL) {
        if (!$date) {
            $date = date(ApicbrfConstants::DATE_FORMAT);
        }
        $this->validateDate($date);
        $query = http_build_query(array(
            ApicbrfConstants::ALL_CURRENCIES_QUOTATIONS_DATE => $date
        ));
        $data = $this->curl->get(ApicbrfConstants::ALL_CURRENCIES_QUOTATIONS_URL . '?' . $query);
        return $this->xmlToArray($data, 'Valute');
    }

    public function getCurrencyByNumCode($numCode, $date = array()) {
        return $this->getCurrencyBy($numCode, 'NumCode', $date);
    }

    public function getCurrencyByCharCode($charCode, $date = array()) {
        return $this->getCurrencyBy($charCode, 'CharCode', $date);
    }

    public function getCurrencyById($id, $date = array()) {
        return $this->getCurrencyBy($id, 'ID', $date);
    }

    protected function getCurrencyBy($key, $column, $date) {
        $currencies = $this->getAllCurrencies($date);

        return $this->searchInArray($currencies, $key, $column);
    }

    protected function searchInArray($array, $needle, $column) {
        $searchArray = array_column($array, $column);
        $index = array_search($needle, $searchArray);
        if (is_int($index)) {
            return $array[$index];
        }
        return array();
    }

    public function getCurrenciesIds($date = NULL) {
        $currencies = $this->getAllCurrencies($date);

        return array_column($currencies, 'ID', 'CharCode');
    }

    public function getCurrencyDynamics($currencyId, $date1, $date2) {
        $this->validateDate($date1);
        $this->validateDate($date2);

        $query = http_build_query(array(
            ApicbrfConstants::CURRENCY_DYNAMICS_QUOTATIONS_DATE1 => $date1,
            ApicbrfConstants::CURRENCY_DYNAMICS_QUOTATIONS_DATE2 => $date2,
            ApicbrfConstants::CURRENCY_DYNAMICS_QUOTATIONS_CURRENCY_ID => $currencyId
        ));
        $data = $this->curl->get(ApicbrfConstants::CURRENCY_DYNAMICS_QUOTATIONS_URL . '?' . $query);

        return $this->xmlToArray($data, 'Record');
    }

    public function getMetalDynamics($date1, $date2) {
        $this->validateDate($date1);
        $this->validateDate($date2);

        $query = http_build_query(array(
            ApicbrfConstants::METAL_DYNAMICS_QUOTATIONS_DATE1 => $date1,
            ApicbrfConstants::METAL_DYNAMICS_QUOTATIONS_DATE2 => $date2
        ));

        $data = $this->curl->get(ApicbrfConstants::DYNAMICS_QUOTATIONS_METAL_URL . '?' . $query);

        return $this->xmlToArray($data, 'Record');
    }

    public function getCurrencyIdByCharCode($charCode, $date = NULL) {
        $currencyIds = $this->getCurrenciesIds($date);

        return isset($currencyIds[$charCode]) ? $currencyIds[$charCode] : FALSE;
    }

    protected function xmlToArray($data, $key) {
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($data);
        if (!$xml && $errors = $this->getXmlErrors()) {

            throw new InvalidXmlFormatException("Error message(s): " . implode(', ', $errors));
        }
        $currencies = array();
        //check incorrect xml 
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

    protected function validateDate($date) {
        $dateTime = \DateTime::createFromFormat(ApicbrfConstants::DATE_FORMAT, $date);
        if ($dateTime) {
            return $dateTime->format(ApicbrfConstants::DATE_FORMAT);
        }
        throw new InvalidDateFormatException(
            "Invalid date format '$date', supported only: " . ApicbrfConstants::DATE_FORMAT
        );
    }

    protected function getXmlErrors() {
        $errors = libxml_get_errors();
        $messages = array();
        foreach ($errors as $error) {
            $type = '';
            switch ($error->level) {
                case LIBXML_ERR_WARNING:
                    $type = "Warning $error->code: ";
                    break;
                case LIBXML_ERR_ERROR:
                    $type = "Error $error->code: ";
                    break;
                case LIBXML_ERR_FATAL:
                    $type = "Fatal Error $error->code: ";
                    break;
            }
            $messages[] = "$type: {$error->message}";
        }
        return $messages;
    }
}
