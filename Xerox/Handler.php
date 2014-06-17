<?php

/**
 * Xerox
 *
 * 
 * Class Handler
 * This class handle the request and pass it to the correct action
 * 
 */

namespace Xerox;

class Handler {

    /**
     * @var Object Self Instance
     */
    private static $instance;

    /**
     * Handler Constructor
     *
     * @return Void
     */
    private function __construct() {
        
    }

    /**
     * Handler getInstance
     *
     * @return Self Object Instance
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Handler();
        }

        return self::$instance;
    }

    /**
     * Handler dispatch
     * To excute the Action function
     *  
     */
    public function dispatch(array $arguments) {
        $action_name = 'Xerox\Action\\' . $this->getName($arguments[0]);
        $action = new $action_name;
        
        return call_user_func_array(array($action, 'execute'), array($arguments));
    }

    /**
     * Handler getName
     * 
     * To convert the first letter of the string in capital
     * @return String 
     */
    public function getName($str) {
        $parts = explode('-', $str);
        foreach ($parts as &$part) {
            $part = ucfirst($part);
        }

        return implode('', $parts);
    }

}