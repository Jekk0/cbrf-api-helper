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

require_once dirname(__FILE__) . "/../src/ApicbrfConstants.php";

/**
 * Class ApicbrfConstants
 * @package Jekk0\Apicbrf
 */
class ApicbrfConstantsTest extends \PHPUnit\Framework\TestCase {

    public function testCheckConstantsType() {
        $constants = array(
            ApicbrfConstants::DATE_FORMAT,
            ApicbrfConstants::ALL_CURRENCIES_QUOTATIONS_URL,
            ApicbrfConstants::ALL_CURRENCIES_QUOTATIONS_DATE,
            ApicbrfConstants::CURRENCY_DYNAMICS_QUOTATIONS_URL,
            ApicbrfConstants::CURRENCY_DYNAMICS_QUOTATIONS_DATE1,
            ApicbrfConstants::CURRENCY_DYNAMICS_QUOTATIONS_DATE2,
            ApicbrfConstants::CURRENCY_DYNAMICS_QUOTATIONS_CURRENCY_ID,
            ApicbrfConstants::DYNAMICS_QUOTATIONS_METAL_URL,
            ApicbrfConstants::METAL_DYNAMICS_QUOTATIONS_DATE1,
            ApicbrfConstants::METAL_DYNAMICS_QUOTATIONS_DATE2
        );
        foreach ($constants as $value) {
            $this->assertTrue(is_string($value));
        }
    }
}
