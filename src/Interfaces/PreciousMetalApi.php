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
interface PreciousMetalApi
{
    public function getDynamics($date1, $date2);

}
