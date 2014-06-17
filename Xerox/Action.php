<?php

/**
 * Xerox
 *
 * 
 * Abstract Class Action
 * This class will load the desired component based on the name found in the 
 * repository URL
 * 
 */

namespace Xerox;

abstract class Action {

    /**
     * @var Object
     */
    protected $Component;

    /**
     * Action Constructor
     *
     * @return Void
     */
    public function __construct() {
        $this->Component = new \stdclass;
    }

    /**
     * Action loadComponent
     *
     * @param String $name Load the component class
     * @return Void
     */
    protected function loadComponent($name) {
        $component_class_name = 'Xerox\Component\\' . $name;
        $this->Component->{$name} = new $component_class_name;
    }

    /**
     * Action execute
     *
     * @param Array $params To parse the arguments passed from the command screen 
     */
    abstract public function execute($params);
    
}