# PHP обертка над API Центрального банка Российской Федерации.
### PHP wrapper for API of Central Bank Of Russian Federation. [Docs in English language](docs/README_EN.md)

###### [Документация для версии 1.x.x](docs/README_VER.1.X.X.md)
###### [Documentation for version 1.x.x](docs/README_EN_VER_1.X.X.md)
[![Build Status](https://travis-ci.org/jekk0/cbrf-api-helper.svg?branch=master)](https://travis-ci.org/jekk0/cbrf-api-helper)
[![Coverage Status](https://codecov.io/gh/jekk0/cbrf-api-helper/branch/master/graphs/badge.svg)](https://codecov.io/gh/jekk0/cbrf-api-helper)
[![Latest Stable Version](https://poser.pugx.org/jekk0/cbrf-api-helper/v/stable)](https://packagist.org/packages/jekk0/cbrf-api-helper)
[![Total Downloads](https://poser.pugx.org/jekk0/cbrf-api-helper/downloads)](https://packagist.org/packages/jekk0/cbrf-api-helper)

### Требования

  * php >=7.0
  * ext-mbstring
  * ext-simplexml
  * ext-libxml

### Установка

 Установка последней версии используя composer

 $ composer require jekk0/cbrf-api-helper

### Быстрый старт.
Подключение пакета и создание обьекта для работы с API
```php
<?php
// Create instance
require_once __DIR__ . "/vendor/autoload.php";

$client = new \Jekk0\Cbrf\Client\Client();
// Получение котировок валют

// На текущую дату
$client->getCurrencyApi()->getAll();

// Получение котировок на указанный день
$client->getCurrencyApi()->getAll("05.12.2010");

// Результат запроса
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

// Получение идентификаторов для валют:
$client->getCurrencyApi()->getIds();

// Результат запроса
// array (size=34)
//   'AUD' => string 'R01010' (length=6)
//   'AZN' => string 'R01020A' (length=7)
//   'GBP' => string 'R01035' (length=6)
//   'AMD' => string 'R01060' (length=6)
//   'BYN' => string 'R01090B' (length=7)
//   'BGN' => string 'R01100' (length=6)
//   'BRL' => string 'R01115' (length=6)
//   ...

// Получение данных по валюте по коду, текстовому коду и идентификатору

$client->getCurrencyApi()->getByNumCode(840);
$client->getCurrencyApi()->getByCharCode('USD');
$client->getCurrencyApi()->getById("R01235");

// Результат запроса
// array (size=6)
//   'NumCode' => string '840' (length=3)
//   'CharCode' => string 'USD' (length=3)
//   'Nominal' => string '1' (length=1)
//   'Name' => string 'Доллар США' (length=19)
//   'Value' => string '58,5182' (length=7)
//   'ID' => string 'R01235' (length=6)

// Получения динамики котировок, в примере для USD

// R01235 - идентификатор валюты
// 01.12.2017 - начальная дата
// 04.12.2017 - конечная дата
$client->getCurrencyApi()->getDynamics('R01235', "01.12.2017", "04.12.2017");

// Результат запроса
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

// Получения изменения курса валют 
// Выходные дни не учитываются, если выбран выходной(суббота или воскресенье) день то изменение курса будет взято и посчитано для пятницы, так как на выходных курс не изменяется  
// Пример:
// 24.02.2018(суббота)
// Разница в курсах будет автоматически расчитана для пятницы
$client->getCurrencyApi()->getDifference('24.02.2018');

// Результат
// array:34 [
//   "AUD" => "-0.1543"
//   "AZN" => "0.0629"
//   "GBP" => "-0.1684"
//   "AMD" => "0.0174"
//   "BYN" => "0.0104"
//   "BGN" => "-0.0425"
//   ...
// ]

// Получения динамики котировок драгоценных металлов

// 25.11.2017 - начальная дата
// 04.12.2017 - конечная дата
$client->getPreciousMetalApi()->getDynamics("25.11.2017", "04.12.2017");

// Результат запроса
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
