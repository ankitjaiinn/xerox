<?php

require APPLICATION_PATH . DS . 'autoload.php';
spl_autoload_register(array('Autoloader', 'load'));

if(PHP_SAPI != 'cli') {
    throw new Exception('This script will run from the command line!');
}

array_shift($argv);

$handler = Xerox\Handler::getInstance();
$handler->dispatch($argv);