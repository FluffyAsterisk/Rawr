<?php

namespace App\Core;

use App\Core\ServiceContainer;

class ServiceInstance {
    public $name;
    private $instance;
    private $loader;

    public function __construct($name, $loader = null) {
        // You can pass to the function: an ID without a callback, an ID with a callback, an object

        $this->name = $name;

        if (!$loader) 
        {
            $this->instance = new $name();
        } 
        elseif ($loader instanceof \Closure) 
        {
            $this->loader = $loader;
        } 
        else 
        {
            $this->instance = $loader;
        }
    }

    public function getService(ServiceContainer $c) {
        if ( !isset($this->instance) ) {
            $this->instance = call_user_func($this->loader, $c);
        }

        return $this->instance;
    }
}