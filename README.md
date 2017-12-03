# In developing ...
# php Api wrapper for Central Bank Of Russian Federation.

# Quick start.
```php
<?php

// Create instance

require_once "vendor/autoload.php";

$cbrf = new \Jekk0\Apicbrf\Apicbrf();
```

### Get all currencies

```php
<?php
// For current date
$cbrf->getAllCurrencies();

// You can set specific date
$cbrf->getAllCurrencies("05.12.2010");
```
// Result
```php
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
```
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

### Get currency by code, char code, id
$cbrf->getCurrencyByCode(840)
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


### Currency dynamics
$cbrf->getCurrencyDynamics('R01235', "25.11.2017", "04.12.2017")

### Metal dynamics
$cbrf->getMetalDynamics("25.11.2017", "04.12.2017")





