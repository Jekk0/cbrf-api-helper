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

use Jekk0\Cbrf\Client\Exceptions\InvalidDateFormatException;
use Jekk0\Cbrf\Client\Interfaces\CbrfConstants;

trait DateValidator
{
    protected function setDefaultDateIfNotSet($date)
    {
        if (!$date) {
            $date = date(CbrfConstants::DATE_FORMAT);
        }

        return $date;
    }

    /**
     * @param $date
     *
     * @return string
     * @throws InvalidDateFormatException
     */
    protected function validateDate($date)
    {

        $dateTime = \DateTime::createFromFormat(CbrfConstants::DATE_FORMAT, $date);

        if ($dateTime) {
            return $dateTime->format(CbrfConstants::DATE_FORMAT);
        }

        throw new InvalidDateFormatException(
            "Invalid date format '$date', supported only: " . CbrfConstants::DATE_FORMAT
        );
    }
}
