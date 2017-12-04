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

/**
 * Class ApicbrfConstants
 * @package Jekk0\Apicbrf
 */
class ApicbrfConstants {
    const ALL_CURRENCIES_QUOTATIONS_URL = "http://www.cbr.ru/scripts/XML_daily.asp";

    const ALL_CURRENCIES_QUOTATIONS_DATE = "date_req";

    const CURRENCY_DYNAMICS_QUOTATIONS_URL = "http://www.cbr.ru/scripts/XML_dynamic.asp";

    const CURRENCY_DYNAMICS_QUOTATIONS_DATE1 = "date_req1";

    const CURRENCY_DYNAMICS_QUOTATIONS_DATE2 = "date_req2";

    const CURRENCY_DYNAMICS_QUOTATIONS_CURRENCY_ID = "VAL_NM_RQ";

    const DYNAMICS_QUOTATIONS_METAL_URL = "http://www.cbr.ru/scripts/xml_metall.asp";

    const METAL_DYNAMICS_QUOTATIONS_DATE1 = "date_req1";

    const METAL_DYNAMICS_QUOTATIONS_DATE2 = "date_req2";
}