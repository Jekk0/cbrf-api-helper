# PHP wrapper for API of Central Bank Of Russian Federation.

###### [Documentation for version 1.x.x](./docs/README_EN_VER_1.X.X.md)

[![Build Status](https://travis-ci.org/jekk0/cbrf-api-helper.svg?branch=master)](https://travis-ci.org/jekk0/cbrf-api-helper)
[![Coverage Status](https://codecov.io/gh/jekk0/cbrf-api-helper/branch/master/graphs/badge.svg)](https://codecov.io/gh/jekk0/cbrf-api-helper)
[![Latest Stable Version](https://poser.pugx.org/jekk0/cbrf-api-helper/v/stable)](https://packagist.org/packages/jekk0/cbrf-api-helper)
[![Total Downloads](https://poser.pugx.org/jekk0/cbrf-api-helper/downloads)](https://packagist.org/packages/jekk0/cbrf-api-helper)

### Requirements

  * php >=7.0
  * ext-mbstring
  * ext-simplexml
  * ext-libxml

### Installation

 Install the latest version with
```
 $ composer require jekk0/cbrf-api-helper
```

### Quick start.
```php
<?php
// Create instance
require_once __DIR__ . "/vendor/autoload.php";

### Dynamics of currency quotes

$client = new \Jekk0\Cbrf\Client\Client();
$client->getCurrencyApi()->getAll();

// You can set specific date
// For current date
$client->getCurrencyApi()->getAll("05.12.2010");

// Result
// array (size=34)
//   0 =>
//     array (size=6)
//       'NumCode' => string '036' (length=3)
//       'CharCode' => string 'AUD' (length=3)
//       'Nominal' => string '1' (length=1)
//       'Name' => string 'Австралийский доллар' (length=39)
//       'Value' => string '44,3861' (length=7)
//       'ID' => string 'R01010' (length=6)
// ...

// Get currencies ids 
$client->getCurrencyApi()->getIds();

// Result
// array (size=34)
//   'AUD' => string 'R01010' (length=6)
//   'AZN' => string 'R01020A' (length=7)
//   'GBP' => string 'R01035' (length=6)
//   'AMD' => string 'R01060' (length=6)
//   'BYN' => string 'R01090B' (length=7)
//   'BGN' => string 'R01100' (length=6)
//   'BRL' => string 'R01115' (length=6)
//   ...

// Get currency by code, char code, id

$client->getCurrencyApi()->getByNumCode(840);
$client->getCurrencyApi()->getByCharCode('USD');
$client->getCurrencyApi()->getById("R01235");

// Result
// array (size=6)
//   'NumCode' => string '840' (length=3)
//   'CharCode' => string 'USD' (length=3)
//   'Nominal' => string '1' (length=1)
//   'Name' => string 'Доллар США' (length=19)
//   'Value' => string '58,5182' (length=7)
//   'ID' => string 'R01235' (length=6)

// Dynamics of currency quotes

// R01235 - currency id
// 01.12.2017 - start date
// 04.12.2017 - finish date
$client->getCurrencyApi()->getDynamics('R01235', "01.12.2017", "04.12.2017");

// Result
// array (size=2)
//   0 =>
//     array (size=4)
//       'Nominal' => string '1' (length=1)
//       'Value' => string '58,5814' (length=7)
//       'Date' => string '01.12.2017' (length=10)
//       'Id' => string 'R01235' (length=6)
//   1 =>
//     array (size=4)
//       'Nominal' => string '1' (length=1)
//       'Value' => string '58,5182' (length=7)
//       'Date' => string '02.12.2017' (length=10)
//       'Id' => string 'R01235' (length=6)
//   ...

// Obtaining a difference in the exchange rate
// If a weekend (Saturday or Sunday) has chosen, the exchange rate will be accepted and calculated
// for Friday, because on the weekend the rate does not change.
//Example:
$client->getCurrencyApi()->getDifference('24.02.2018');

// Result

// array:34 [
//   "AUD" => "-0.1543"
//   "AZN" => "0.0629"
//   "GBP" => "-0.1684"
//   "AMD" => "0.0174"
//   "BYN" => "0.0104"
//   "BGN" => "-0.0425"
//   ...
// ]

// Dynamics of precious metal quotes

// 25.11.2017 - начальная дата
// 04.12.2017 - конечная дата
$client->getPreciousMetalApi()->getDynamics("25.11.2017", "04.12.2017");

// Result
// array (size=8)
//   0 =>
//     array (size=4)
//       'Buy' => string '2414,85' (length=7)
//       'Sell' => string '2414,85' (length=7)
//       'Date' => string '01.12.2017' (length=10)
//       'Code' => string '1' (length=1)
//   1 =>
//     array (size=4)
//       'Buy' => string '31,82' (length=5)
//       'Sell' => string '31,82' (length=5)
//       'Date' => string '01.12.2017' (length=10)
//       'Code' => string '2' (length=1)
//   2 =>
//     array (size=4)
//       'Buy' => string '1772,31' (length=7)
//       'Sell' => string '1772,31' (length=7)
//       'Date' => string '01.12.2017' (length=10)
//       'Code' => string '3' (length=1)
//   3 =>
//     array (size=4)
//       'Buy' => string '1917,34' (length=7)
//       'Sell' => string '1917,34' (length=7)
//       'Date' => string '01.12.2017' (length=10)
//       'Code' => string '4' (length=1)
//   ....
```
