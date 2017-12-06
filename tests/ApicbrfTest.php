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

require_once dirname(__FILE__) . "/ApicbrfOverrideConstructor.php";


use Jekk0\Apicbrf\Exceptions\InvalidDateFormatException;
use Jekk0\Apicbrf\Exceptions\InvalidRequestParamsException;
use Jekk0\Apicbrf\Exceptions\InvalidXmlFormatException;

class ApicbrfTest extends \PHPUnit\Framework\TestCase {

    protected $instance;

    public function setUp() {
        $this->instance = new ApicbrfOverrideConstructor();
    }

    public function tearDown() {
        $this->instance = NULL;
    }

    public function testInstance() {
        $this->assertTrue($this->instance instanceof Apicbrf);
    }

    /**
     * @dataProvider testGetAllCurrenciesDataProvider
     */
    public function testGetAllCurrencies($xml) {
        $this->instance->setCurlContent($xml);
        $this->assertTrue(is_array($this->instance->getAllCurrencies()));
    }
    
    public function testGetAllCurrenciesDataProvider() {
        return array(
            array("<?xml version='1.0' encoding='UTF-8'?><ROOT></ROOT>"),
//            array("Wrong xml format"),
//            array(""),
//            array("1234556789")
        );
    }

    /**
     * @expectedException Jekk0\Apicbrf\Exceptions\InvalidXmlFormatException
     */
    public function testGetAllCurrenciesException() {
        $this->instance->setCurlContent('Wrong xml format');
        $this->instance->getAllCurrencies();
    }

    public function testGetCurrencyByNumCode() {
        //$this->assertTrue(is_array($this->instance->getAllCurrencies()));
    }

    public function testGetCurrencyByCharCode() {
        //$this->assertTrue(is_array($this->instance->getAllCurrencies()));
    }

    public function testGetCurrencyById() {
        //$this->assertTrue(is_array($this->instance->getAllCurrencies()));
    }

    public function testGetCurrenciesIds() {
        //$this->assertTrue(is_array($this->instance->getAllCurrencies()));
    }

    public function testGetCurrencyDynamics() {
        //$this->assertTrue(is_array($this->instance->getAllCurrencies()));
    }

    public function testGetMetalDynamics() {
        //$this->assertTrue(is_array($this->instance->getAllCurrencies()));
    }

    public function testGetCurrencyIdByCharCode() {
        //$this->assertTrue(is_array($this->instance->getAllCurrencies()));
    }
}
