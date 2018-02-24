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

class ApicbrfTest extends \PHPUnit\Framework\TestCase
{

    protected $instance;

    public function setUp()
    {
        define('RESOURCE_FOLDER', dirname(__FILE__) . '/resources');
        $this->instance = new ApicbrfOverrideConstructor();
        $this->instance->setCurlContent(file_get_contents(RESOURCE_FOLDER . '/AllCurrencies.xml'));
    }

    public function tearDown()
    {
        $this->instance = null;
    }

    public function testInstance()
    {
        $this->assertTrue($this->instance instanceof Apicbrf);
    }

    /**
     * @dataProvider testGetAllCurrenciesDataProvider
     */
    public function testGetAllCurrencies($xml)
    {
        $this->instance->setCurlContent($xml);
        $this->assertTrue(is_array($this->instance->getAllCurrencies()));
    }

    public function testGetAllCurrenciesDataProvider()
    {
        return array(
            array("<?xml version='1.0' encoding='UTF-8'?><ROOT></ROOT>"),
            array(
                '<?xml version="1.0" encoding="utf-8" ?>
                    <ValCurs Date="06.12.2017" name="Foreign Currency Market">
                    <Valute ID="R01010">
                    <NumCode>036</NumCode>
                    <CharCode>AUD</CharCode>
                    <Nominal>1</Nominal>
                    <Name>Австралийский доллар</Name>
                    <Value>44,8762</Value>
                </Valute>
                <Valute ID="R01020A">
                    <NumCode>944</NumCode>
                    <CharCode>AZN</CharCode>
                    <Nominal>1</Nominal>
                    <Name>Азербайджанский манат</Name>
                    <Value>34,5249</Value>
                </Valute>
                </ValCurs>'
            ),
            array("<?xml version='1.0' encoding='UTF-8'?><MainTag><SubTag></SubTag></MainTag>"),
        );
    }

    /**
     * @expectedException Jekk0\Apicbrf\Exceptions\InvalidXmlFormatException
     */
    public function testGetAllCurrenciesInvalidXmlException()
    {
        $this->instance->setCurlContent('Wrong xml format');
        $this->instance->getAllCurrencies();
    }

    /**
     * @expectedException Jekk0\Apicbrf\Exceptions\InvalidDateFormatException
     */
    public function testGetAllCurrenciesInvalidDateFormatException()
    {
        $this->instance->setCurlContent('<?xml version="1.0" encoding="UTF-8"?><Test></Test>');
        $this->instance->getAllCurrencies('20-12-2017');
    }

    /**
     * @expectedException Jekk0\Apicbrf\Exceptions\InvalidRequestParamsException
     */
    public function testGetAllCurrenciesInvalidRequestParamsException()
    {
        $this->instance->setCurlContent('<?xml version="1.0" encoding="utf-8" ?><ValCurs>Error in parameters</ValCurs>');
        $this->instance->getAllCurrencies();
    }

    public function testGetCurrencyByNumCode()
    {
        $array = array(
            'NumCode' => '840',
            'CharCode' => 'USD',
            'Nominal' => '1',
            'Name' => 'Доллар США',
            'Value' => '58,6924',
            'ID' => 'R01235',
        );
        $this->assertEquals($array, $this->instance->getCurrencyByNumCode(840));
        $this->assertEquals(array(), $this->instance->getCurrencyByNumCode(99999));

    }

    public function testGetCurrencyByCharCode()
    {
        $array = array(
            'NumCode' => '840',
            'CharCode' => 'USD',
            'Nominal' => '1',
            'Name' => 'Доллар США',
            'Value' => '58,6924',
            'ID' => 'R01235',
        );
        $this->assertEquals($array, $this->instance->getCurrencyByCharCode('USD'));
        $this->assertEquals(array(), $this->instance->getCurrencyByCharCode(123));
        $this->assertEquals(array(), $this->instance->getCurrencyByCharCode('usd'));
    }

    public function testGetCurrencyById()
    {
        $array = array(
            'NumCode' => '840',
            'CharCode' => 'USD',
            'Nominal' => '1',
            'Name' => 'Доллар США',
            'Value' => '58,6924',
            'ID' => 'R01235',
        );
        $this->assertEquals($array, $this->instance->getCurrencyById('R01235'));
        $this->assertEquals(array(), $this->instance->getCurrencyById(''));
        $this->assertEquals(array(), $this->instance->getCurrencyById('r01235'));
    }

    public function testGetCurrenciesIds()
    {
        $array = array(
            'AUD' => 'R01010',
            'AZN' => 'R01020A',
            'GBP' => 'R01035',
            'AMD' => 'R01060',
            'BYN' => 'R01090B',
            'BGN' => 'R01100',
            'BRL' => 'R01115',
            'HUF' => 'R01135',
            'HKD' => 'R01200',
            'DKK' => 'R01215',
            'USD' => 'R01235',
            'EUR' => 'R01239',
            'INR' => 'R01270',
            'KZT' => 'R01335',
            'CAD' => 'R01350',
            'KGS' => 'R01370',
            'CNY' => 'R01375',
            'MDL' => 'R01500',
            'NOK' => 'R01535',
            'PLN' => 'R01565',
            'RON' => 'R01585F',
            'XDR' => 'R01589',
            'SGD' => 'R01625',
            'TJS' => 'R01670',
            'TRY' => 'R01700J',
            'TMT' => 'R01710A',
            'UZS' => 'R01717',
            'UAH' => 'R01720',
            'CZK' => 'R01760',
            'SEK' => 'R01770',
            'CHF' => 'R01775',
            'ZAR' => 'R01810',
            'KRW' => 'R01815',
            'JPY' => 'R01820',
        );
        $this->assertEquals($array, $this->instance->getCurrenciesIds());
    }

    public function testGetCurrencyDynamics()
    {
        $array = array(
            array(
                'Nominal' => '1',
                'Value' => '58,5814',
                'Date' => '01.12.2017',
                'Id' => 'R01235'
            ),
            array(
                'Nominal' => '1',
                'Value' => '58,5182',
                'Date' => '02.12.2017',
                'Id' => 'R01235',
            )
        );
        $this->instance->setCurlContent(file_get_contents(RESOURCE_FOLDER . '/CurrencyDynamicsQuotes.xml'));
        $this->assertEquals($array, $this->instance->getCurrencyDynamics('R01235', "01.12.2017", "04.12.2017"));
    }

    public function testGetMetalDynamics()
    {
        $array = array(

            array(
                'Buy' => '2414,85',
                'Sell' => '2414,85',
                'Date' => '01.12.2017',
                'Code' => '1'
            ),
            array(
                'Buy' => '31,82',
                'Sell' => '31,82',
                'Date' => '01.12.2017',
                'Code' => '2'
            ),

            array(
                'Buy' => '1772,31',
                'Sell' => '1772,31',
                'Date' => '01.12.2017',
                'Code' => '3'
            ),
            array(
                'Buy' => '1917,34',
                'Sell' => '1917,34',
                'Date' => '01.12.2017',
                'Code' => '4'
            )
        );
        $this->instance->setCurlContent(file_get_contents(RESOURCE_FOLDER . '/MetalDynamicsQuotes.xml'));
        $this->assertEquals($array, $this->instance->getMetalDynamics("01.12.2017", "01.12.2017"));
    }

    public function testGetCurrencyIdByCharCode()
    {
        $this->assertEquals('R01035', $this->instance->getCurrencyIdByCharCode('GBP'));
        $this->assertFalse($this->instance->getCurrencyIdByCharCode('WRONG'));
        $this->assertFalse($this->instance->getCurrencyIdByCharCode('R01035'));
    }
}
