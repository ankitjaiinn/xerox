<?php

/**
 * Xerox
 *
 * 
 * This is the starting file which will instantiate the process
 * 
 */

define('APPLICATION_PATH', dirname(__FILE__));
define('DS', '/');

require APPLICATION_PATH . DS . 'Xerox/Issue.php';

try {

    if (PHP_SAPI != 'cli') {
        throw new Exception('This script will run from the command line!');
    }

    array_shift($argv);
	
    $issue = new Issue($argv);
    echo $issue->execute();
	
} catch (Exception $e) {
    echo "==========================================================================\n";
    echo "Error : " . $e->getMessage() . "\n";
    echo "==========================================================================\n";
}