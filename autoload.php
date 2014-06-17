<?php

/**
 * Xerox
 *
 * 
 * Class Autoloader 
 * This class is used to define our own autoload function to include the class
 * 
 */

class Autoloader {

    /**
     * load class file by it's full name
     *
     * @param string $class
     * @return void
     */
    public static function load($class) {
        $parts = explode('\\', $class);
        $filename = APPLICATION_PATH . DS . implode(DS, $parts) . '.php';
        if (file_exists($filename)) {
            require_once $filename;
        } else {
			throw new Exception('Method is undefined!');
		}
		
    }

}