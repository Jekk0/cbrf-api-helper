<?php
/**
 * PHP wrapper for Central Bank Of Russian Federation Api.
 *
 * @package    Jekk0/cbrf-api-helper
 * @author     Jekko https://github.com/Jekk0
 * @license    MIT
 * @link       https://github.com/Jekk0/cbrf-api-helper
 */

namespace Jekk0\Cbrf\Client;


use Jekk0\Cbrf\Client\Interfaces\HttpClientApi;

define('RESOURCE_FOLDER', __DIR__ . '/resources');

class ClientTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Client
     */
    protected $client;

    public function setUp()
    {
        $this->client = new Client();
    }

    public function tearDown()
    {
        $this->client = null;
    }

    /**
     * @dataProvider getAllCurrenciesDataProvider
     */
    public function testGetAllCurrencies($xml)
    {
        $httpClient =  $this->createMock(HttpClientApi::class);
        $httpClient->method('get')->willReturn($xml);
        $this->client->setHttpClient($httpClient);

        $this->assertTrue(is_array($this->client->getCurrencyApi()->getAll()));
    }

    public function getAllCurrenciesDataProvider()
    {
        return array(array("<?xml version='1.0' encoding='UTF-8'?><ROOT></ROOT>"), array('<?xml version="1.0" encoding="utf-8" ?>
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
                </ValCurs>'), array("<?xml version='1.0' encoding='UTF-8'?><MainTag><SubTag></SubTag></MainTag>"),);
    }

    /**
     * @expectedException \Jekk0\Cbrf\Client\Exceptions\InvalidXmlFormatException
     */
    public function testGetAllCurrenciesInvalidXmlException()
    {
        $httpClient =  $this->createMock(HttpClientApi::class);
        $httpClient->method('get')->willReturn('Wrong xml format');
        $this->client->setHttpClient($httpClient);

        $this->client->getCurrencyApi()->getAll();
    }

    /**
     * @expectedException \Jekk0\Cbrf\Client\Exceptions\InvalidDateFormatException
     */
    public function testGetAllCurrenciesInvalidDateFormatException()
    {
        $httpClient =  $this->createMock(HttpClientApi::class);
        $httpClient->method('get')->willReturn('<?xml version="1.0" encoding="UTF-8"?><Test></Test>');
        $this->client->setHttpClient($httpClient);

        $this->client->getCurrencyApi()->getAll('20-12-2017');
    }

    /**
     * @expectedException \Jekk0\Cbrf\Client\Exceptions\InvalidRequestParamsException
     */
    public function testGetAllCurrenciesInvalidRequestParamsException()
    {
        $httpClient =  $this->createMock(HttpClientApi::class);
        $httpClient->method('get')->willReturn('<?xml version="1.0" encoding="utf-8" ?><ValCurs>Error in parameters</ValCurs>');
        $this->client->setHttpClient($httpClient);

        $this->client->getCurrencyApi()->getAll();
    }

    public function testGetCurrencyByNumCode()
    {
        $array = array('NumCode' => '840', 'CharCode' => 'USD', 'Nominal' => '1', 'Name' => 'Доллар США',
            'Value' => '58.6924', 'ID' => 'R01235',);
        $httpClient =  $this->createMock(HttpClientApi::class);
        $httpClient->method('get')->willReturn(file_get_contents(RESOURCE_FOLDER . '/AllCurrencies.xml'));
        $this->client->setHttpClient($httpClient);
        $this->assertEquals($array, $this->client->getCurrencyApi()->getByNumCode(840));
        $this->assertEquals(array(), $this->client->getCurrencyApi()->getByNumCode(99999));

    }

    public function testGetCurrencyByCharCode()
    {
        $array = array('NumCode' => '840', 'CharCode' => 'USD', 'Nominal' => '1', 'Name' => 'Доллар США',
            'Value' => '58.6924', 'ID' => 'R01235',);

        $httpClient =  $this->createMock(HttpClientApi::class);
        $httpClient->method('get')->willReturn(file_get_contents(RESOURCE_FOLDER . '/AllCurrencies.xml'));
        $this->client->setHttpClient($httpClient);

        $this->assertEquals($array, $this->client->getCurrencyApi()->getByCharCode('USD'));
        $this->assertEquals(array(), $this->client->getCurrencyApi()->getByCharCode(123));
        $this->assertEquals(array(), $this->client->getCurrencyApi()->getByCharCode('usd'));
    }

    public function testGetCurrencyById()
    {
        $array = array('NumCode' => '840', 'CharCode' => 'USD', 'Nominal' => '1', 'Name' => 'Доллар США',
            'Value' => '58.6924', 'ID' => 'R01235',);

        $httpClient =  $this->createMock(HttpClientApi::class);
        $httpClient->method('get')->willReturn(file_get_contents(RESOURCE_FOLDER . '/AllCurrencies.xml'));
        $this->client->setHttpClient($httpClient);

        $this->assertEquals($array, $this->client->getCurrencyApi()->getById('R01235'));
        $this->assertEquals(array(), $this->client->getCurrencyApi()->getById(''));
        $this->assertEquals(array(), $this->client->getCurrencyApi()->getById('r01235'));
    }

    public function testGetCurrenciesIds()
    {
        $array = array('AUD' => 'R01010', 'AZN' => 'R01020A', 'GBP' => 'R01035', 'AMD' => 'R01060', 'BYN' => 'R01090B',
            'BGN' => 'R01100', 'BRL' => 'R01115', 'HUF' => 'R01135', 'HKD' => 'R01200', 'DKK' => 'R01215',
            'USD' => 'R01235', 'EUR' => 'R01239', 'INR' => 'R01270', 'KZT' => 'R01335', 'CAD' => 'R01350',
            'KGS' => 'R01370', 'CNY' => 'R01375', 'MDL' => 'R01500', 'NOK' => 'R01535', 'PLN' => 'R01565',
            'RON' => 'R01585F', 'XDR' => 'R01589', 'SGD' => 'R01625', 'TJS' => 'R01670', 'TRY' => 'R01700J',
            'TMT' => 'R01710A', 'UZS' => 'R01717', 'UAH' => 'R01720', 'CZK' => 'R01760', 'SEK' => 'R01770',
            'CHF' => 'R01775', 'ZAR' => 'R01810', 'KRW' => 'R01815', 'JPY' => 'R01820',);
        $httpClient =  $this->createMock(HttpClientApi::class);
        $httpClient->method('get')->willReturn(file_get_contents(RESOURCE_FOLDER . '/AllCurrencies.xml'));
        $this->client->setHttpClient($httpClient);

        $this->assertEquals($array, $this->client->getCurrencyApi()->getIds());
    }

    public function testGetCurrencyDynamics()
    {
        $array = array(array('Nominal' => '1', 'Value' => '58.5814', 'Date' => '01.12.2017', 'Id' => 'R01235'),
            array('Nominal' => '1', 'Value' => '58.5182', 'Date' => '02.12.2017', 'Id' => 'R01235',));
        $httpClient =  $this->createMock(HttpClientApi::class);
        $httpClient->method('get')->willReturn(file_get_contents(RESOURCE_FOLDER . '/CurrencyDynamicsQuotes.xml'));
        $this->client->setHttpClient($httpClient);

        $this->assertEquals($array, $this->client->getCurrencyApi()->getDynamics('R01235', "01.12.2017", "04.12.2017"));
    }

    public function testGetMetalDynamics()
    {
        $array = array(

            array('Buy' => '2414.85', 'Sell' => '2414.85', 'Date' => '01.12.2017', 'Code' => '1'),
            array('Buy' => '31.82', 'Sell' => '31.82', 'Date' => '01.12.2017', 'Code' => '2'),

            array('Buy' => '1772.31', 'Sell' => '1772.31', 'Date' => '01.12.2017', 'Code' => '3'),
            array('Buy' => '1917.34', 'Sell' => '1917.34', 'Date' => '01.12.2017', 'Code' => '4'));
        $httpClient =  $this->createMock(HttpClientApi::class);
        $httpClient->method('get')->willReturn(file_get_contents(RESOURCE_FOLDER . '/MetalDynamicsQuotes.xml'));
        $this->client->setHttpClient($httpClient);

        $this->assertEquals($array, $this->client->getPreciousMetalApi()->getDynamics("01.12.2017", "01.12.2017"));
    }

    public function testGetCurrencyIdByCharCode()
    {
        var_dump($this->client->getCurrencyApi()->getByCharCode('GBP'));
        $this->assertEquals('R01035', $this->client->getCurrencyApi()->getIdByCharCode('GBP'));
        $this->assertFalse($this->client->getCurrencyApi()->getIdByCharCode('WRONG'));
        $this->assertFalse($this->client->getCurrencyApi()->getIdByCharCode('R01035'));
    }
}
