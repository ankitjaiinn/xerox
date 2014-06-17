<?php

namespace Xerox; 

class Handler
{
    private static $instance;
    
    private function __construct() {}
    
    public static function getInstance() {
        if( self::$instance === null ) {
            self::$instance = new Handler();
        }
        
        return self::$instance;
    }
    
    public function dispatch(array $arguments) {
        $action_name = 'Xerox\Action\\' . $this->getName($arguments[0]);
        $action = new $action_name;
        return call_user_func_array(array($action, 'execute'), array($arguments));
    }
    
    public function getName($str) {
        $parts = explode('-', $str);
        foreach( $parts as &$part ) {
            $part = ucfirst($part);
        }
        
        return implode('', $parts);
    }
}