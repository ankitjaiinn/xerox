<?php

/**
 * Xerox
 *
 * 
 * This file check the script execution and load the autoloader class
 * It also dispatch the required action method
 * 
 */

require APPLICATION_PATH . DS . 'autoload.php';
spl_autoload_register(array('Autoloader', 'load'));

try {

    if (PHP_SAPI != 'cli') {
        throw new Exception('This script will run from the command line!');
    }

    array_shift($argv);

    $handler = Xerox\Handler::getInstance();
    $handler->dispatch($argv);
    
} catch (Exception $e) {
    echo $e->getMessage();
}