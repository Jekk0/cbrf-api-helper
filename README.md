# PHP wrapper for API of Central Bank Of Russian Federation.
## PHP обертка над API Центрального банка Российской Федерации. [Документация на русском языке](./README_RU.md)
[![Build Status](https://travis-ci.org/Jekk0/cbrf-api-helper.svg?branch=master)](https://travis-ci.org/Jekk0/cbrf-api-helper)
[![Coverage Status](https://codecov.io/gh/Jekk0/cbrf-api-helper/branch/master/graphs/badge.svg)](https://codecov.io/gh/Jekk0/cbrf-api-helper)
[![Latest Stable Version](https://poser.pugx.org/jekk0/cbrf-api-helper/v/stable)](https://packagist.org/packages/jekk0/cbrf-api-helper)
[![Total Downloads](https://poser.pugx.org/jekk0/cbrf-api-helper/downloads)](https://packagist.org/packages/jekk0/cbrf-api-hecbrf-api-helper)

### Requirements

  * php >=5.5
  * cURL extension
  * SimpleXML extension

### Installation

 Install the latest version with
```
 $ composer require jekk0/cbrf-api-helper
```
### Quick start.
```php
<?php
// Create instance
require_once "vendor/autoload.php";

$cbrf = new \Jekk0\Apicbrf\Apicbrf();
```

### Get all currencies
```php
// For current date
$cbrf->getAllCurrencies();

// You can set specific date
$cbrf->getAllCurrencies("05.12.2010");

// Result
array (size=34)
  0 =>
    array (size=6)
      'NumCode' => string '036' (length=3)
      'CharCode' => string 'AUD' (length=3)
      'Nominal' => string '1' (length=1)
      'Name' => string 'Австралийский доллар' (length=39)
      'Value' => string '44,3861' (length=7)
      'ID' => string 'R01010' (length=6)
...
```
### Get all currency ids:

```php
$cbrf->getCurrenciesIds();

//Result
array (size=34)
  'AUD' => string 'R01010' (length=6)
  'AZN' => string 'R01020A' (length=7)
  'GBP' => string 'R01035' (length=6)
  'AMD' => string 'R01060' (length=6)
  'BYN' => string 'R01090B' (length=7)
  'BGN' => string 'R01100' (length=6)
  'BRL' => string 'R01115' (length=6)
  ...
```
### Get currency by code, char code, id
```php
$cbrf->getCurrencyByNumCode(840)
$cbrf->getCurrencyByCharCode('USD')
$cbrf->getCurrencyById("R01235")

//Result
array (size=6)
  'NumCode' => string '840' (length=3)
  'CharCode' => string 'USD' (length=3)
  'Nominal' => string '1' (length=1)
  'Name' => string 'Доллар США' (length=19)
  'Value' => string '58,5182' (length=7)
  'ID' => string 'R01235' (length=6)
```
### Dynamics of currency quotes
```php
$cbrf->getCurrencyDynamics('R01235', "01.12.2017", "04.12.2017")

//Result
array (size=2)
  0 =>
    array (size=4)
      'Nominal' => string '1' (length=1)
      'Value' => string '58,5814' (length=7)
      'Date' => string '01.12.2017' (length=10)
      'Id' => string 'R01235' (length=6)
  1 =>
    array (size=4)
      'Nominal' => string '1' (length=1)
      'Value' => string '58,5182' (length=7)
      'Date' => string '02.12.2017' (length=10)
      'Id' => string 'R01235' (length=6)
  ...
```

### Dynamics of precious metal quotes
```php
$cbrf->getMetalDynamics("25.11.2017", "04.12.2017")

//Result
array (size=8)
  0 =>
    array (size=4)
      'Buy' => string '2414,85' (length=7)
      'Sell' => string '2414,85' (length=7)
      'Date' => string '01.12.2017' (length=10)
      'Code' => string '1' (length=1)
  1 =>
    array (size=4)
      'Buy' => string '31,82' (length=5)
      'Sell' => string '31,82' (length=5)
      'Date' => string '01.12.2017' (length=10)
      'Code' => string '2' (length=1)
  2 =>
    array (size=4)
      'Buy' => string '1772,31' (length=7)
      'Sell' => string '1772,31' (length=7)
      'Date' => string '01.12.2017' (length=10)
      'Code' => string '3' (length=1)
  3 =>
    array (size=4)
      'Buy' => string '1917,34' (length=7)
      'Sell' => string '1917,34' (length=7)
      'Date' => string '01.12.2017' (length=10)
      'Code' => string '4' (length=1)
  ....
```





