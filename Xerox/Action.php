<?php

namespace Xerox;

abstract class Action
{
    protected $Component;
    
    public function __construct() {
        $this->Component = new \stdclass;
    }
    
    protected function loadComponent($name) {
        $component_class_name = 'Xerox\Component\\' . $name;
        $this->Component->{$name} = new $component_class_name;
    }
    
    abstract public function execute($params);

}