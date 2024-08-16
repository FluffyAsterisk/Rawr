<?php

namespace App\Core;

use Psr\Container\ContainerInterface;

class ServiceContainer implements ContainerInterface {
    private static $instance;
    private $allocator = [];

    public static function init() {
        if ( !isset(self::$instance) ) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }

    public function bind($id, $loader) {
        if ( $this->has($id) ) 
        {
            throw new \Exception('Service already exists');
        } 
        else 
        {
            $this->allocator[$id] = new ServiceInstance($id, $loader);
        }
    }

    public function get($id) {
        if ( !$this->has($id) ) {
            throw new \Exception("{$id} doesn't exist in service container");
        }

        return $this->allocator[$id]->getService($this);
    }

    public function has($id): bool {
        return array_key_exists( $id, $this->allocator );
    }
}