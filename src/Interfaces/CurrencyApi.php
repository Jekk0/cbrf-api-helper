<?php
/**
 * PHP wrapper for Central Bank Of Russian Federation Api.
 *
 * @package    Jekk0/cbrf-api-helper
 * @author     Jekko https://github.com/Jekk0
 * @license    MIT
 * @link       https://github.com/Jekk0/cbrf-api-helper
 */

namespace Jekk0\Cbrf\Client\Interfaces;

/**
 * Interface CbrfConstants
 * @package Jekk0\Cbrf\Client
 */
interface CurrencyApi
{
    public function getAll($date = null);
    public function getByNumCode($numCode, $date = null);
    public function getByCharCode($charCode, $date = null);
    public function getById($id, $date = null);
    public function getIds($date = null);
    public function getDifference($date = null);
    public function getIdByCharCode($charCode, $date = null);
    public function getDynamics($currencyId, $date1, $date2);
}
