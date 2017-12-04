<?php

$loader = function($class) {
    $fileName = $class . 'php';
    if (file_exists($fileName)) {
        require_once $fileName;
    }
};

spl_autoload_register($loader);