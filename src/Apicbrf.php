<?php

namespace Jekk0\Apicbrf;


class Apicbrf {

    protected $curl;

    public function __construct() {
        $this->curl = new CurlInstance();
    }

    public function getAllCurrencies($date = NULL) {
        if (!$date) {
            $date = date('d.m.Y');
        }
        $query = http_build_query(array(
            ApicbrfConstants::ALL_CURRENCIES_QUOTATIONS_DATE => $date
        ));
        $data = $this->curl->get(ApicbrfConstants::ALL_CURRENCIES_QUOTATIONS_URL . '?' . $query);

        return $this->xmlToArray($data, 'Valute');
    }

    public function getCurrencyByCode($numCode, $date = array()) {
        return $this->getCurrencyBy($numCode, $date);
    }

    public function getCurrencyByCharCode($charCode, $date = array()) {
        return $this->getCurrencyBy($charCode, $date);
    }

    public function getCurrencyById($id, $date = array()) {
        return $this->getCurrencyBy($id, $date);
    }

    protected function getCurrencyBy($key, $date) {
        $currencies = $this->getAllCurrencies($date);

        return $this->searchInArray($currencies, $key);
    }

    protected function searchInArray($array, $needle) {
        $result = array_filter($array, function ($subArray) use ($needle) {
            return array_search($needle, $subArray);
        });

        return array_shift($result);
    }

    public function getCurrenciesIds($date = NULL) {
        $currencies = $this->getAllCurrencies($date);

        return array_column($currencies, 'ID', 'CharCode');
    }

    public function getCurrencyDynamics($currencyId, $date1, $date2) {
        $query = http_build_query(array(
            ApicbrfConstants::CURRENCY_DYNAMICS_QUOTATIONS_DATE1 => $date1,
            ApicbrfConstants::CURRENCY_DYNAMICS_QUOTATIONS_DATE2 => $date2,
            ApicbrfConstants::CURRENCY_DYNAMICS_QUOTATIONS_CURRENCY_ID => $currencyId
        ));
        $data = $this->curl->get(ApicbrfConstants::CURRENCY_DYNAMICS_QUOTATIONS_URL . '?' . $query);

        return $this->xmlToArray($data, 'Record');
    }

    public function getMetalDynamics($date1, $date2) {
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
        $xml = simplexml_load_string($data);
        $currencies = array();
        foreach ($xml->$key as $currency) {
            $currency = (array)$currency;
            $attributes = array_shift($currency);
            $currency = $currency + $attributes;
            $currencies[] = $currency;
        }

        return $currencies;
    }
}