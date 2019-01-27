<?php
/**
 * PHP wrapper for Central Bank Of Russian Federation Api.
 *
 * @package    Jekk0/cbrf-api-helper
 * @author     Jekko https://github.com/Jekk0
 * @license    MIT
 * @link       https://github.com/Jekk0/cbrf-api-helper
 */

namespace Jekk0\Cbrf\Client\Traits;

use Jekk0\Cbrf\Client\Exceptions\InvalidRequestParamsException;
use Jekk0\Cbrf\Client\Exceptions\InvalidXmlFormatException;

trait XmlResponseParser
{
    /**
     * @param $data
     * @param $key
     *
     * @return array
     *
     * @throws InvalidRequestParamsException
     * @throws InvalidXmlFormatException
     */
    protected function xmlToArray($data, $key)
    {
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($data);

        if (!$xml && $errors = $this->getXmlErrors()) {
            throw new InvalidXmlFormatException("Error message(s): " . implode(', ', $errors));
        }
        $currencies = [];
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

    protected function getXmlErrors()
    {
        $errors = libxml_get_errors();
        $messages = [];
        foreach ($errors as $error) {
            $messages[] = "Error: {$error->message}";
        }

        return $messages;
    }

    protected function w1251ToUtf8($string)
    {
        return mb_convert_encoding($string, 'utf-8', 'windows-1251');
    }

    protected function searchInArray($array, $needle, $column)
    {
        $searchArray = array_column($array, $column);
        $index = array_search($needle, $searchArray);
        if (is_int($index)) {
            return $array[$index];
        }

        return [];
    }

    protected function stringValuesToFloat($array, array $arrayKeys)
    {
        $array = array_map(
            function ($currency) use ($arrayKeys) {
                foreach ($arrayKeys as $arrayKey) {
                    $value = str_replace(',', '.', $currency[$arrayKey]);
                    $currency[$arrayKey] = (double)$value;
                }

                return $currency;
            },
            $array
        );

        return $array;
    }
}
