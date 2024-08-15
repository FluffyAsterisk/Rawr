<?php

namespace App\Core;

class ServiceContainer {
    private static $instance;
    private $allocator = [];

    public static function init() {
        if ( !isset(self::$instance) ) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }

    public function bind($name, $fn) {
        if ( array_key_exists($name, $this->allocator) ) {
            throw new \Exception('Service already exists');
        } else {
            $this->allocator[$name] = $fn;
        }
    }

    public function get($name) {
        array_key_exists($name, $this->allocator) ??
            throw new \Exception("{$name} doesn't exist in service container");

        return $this->allocator[$name];
    }
}