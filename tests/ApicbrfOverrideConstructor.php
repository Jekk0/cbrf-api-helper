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

require_once dirname(__FILE__) . "/../src/Apicbrf.php";
require_once dirname(__FILE__) . "/../src/ApicbrfConstants.php";
require_once dirname(__FILE__) . "/../src/Exceptions/InvalidXmlFormatException.php";
require_once dirname(__FILE__) . "/../src/Exceptions/InvalidDateFormatException.php";
require_once dirname(__FILE__) . "/../src/Exceptions/InvalidRequestParamsException.php";

class ApicbrfOverrideConstructor extends Apicbrf {
    
    protected $curl;
    
    public function __construct() {
        $this->curl = new class() {
            
            public $content = '';
            
            public function get() {
                return $this->content;
            }
            
            public function post() {
                return $this->content;
            }
        };
    }

    public function setCurlContent($content) {
        $this->curl->content = $content;
    }
}
