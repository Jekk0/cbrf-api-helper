<?php

require_once __DIR__ . "/../src/Client.php";

/*Interfaces*/
require_once __DIR__ . "/../src/Interfaces/CbrfConstants.php";
require_once __DIR__ . "/../src/Interfaces/HttpClientApi.php";
require_once __DIR__ . "/../src/Interfaces/CurrencyApi.php";
require_once __DIR__ . "/../src/Interfaces/PreciousMetalApi.php";
/* Exceptions */
require_once __DIR__ . "/../src/Exceptions/InvalidXmlFormatException.php";
require_once __DIR__ . "/../src/Exceptions/InvalidDateFormatException.php";
require_once __DIR__ . "/../src/Exceptions/InvalidRequestParamsException.php";
/* Traits */
require_once __DIR__ . "/../src/Traits/XmlResponseParser.php";
require_once __DIR__ . "/../src/Traits/DateValidator.php";
/* Entities */
require_once __DIR__ . "/../src/Entities/Currency.php";
require_once __DIR__ . "/../src/Entities/PreciousMetal.php";
