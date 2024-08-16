<?php

namespace App\Core;

use Psr\Container\ContainerInterface;
use App\Exceptions\ContainerException;

class ServiceContainer implements ContainerInterface {
    private static $instance;
    private $allocator = [];

    public static function init() {
        if ( !isset(self::$instance) ) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }

    public function bind($id, $loader = null) {
        if ( $this->has($id) ) 
        {
            throw new \Exception("Service {$id} already exists");
        } 
        else 
        {
            $this->allocator[$id] = new ServiceInstance($id, $loader);
        }
    }

    public function get($id) {
        if ( $this->has($id) ) {
            return $this->allocator[$id]->getService($this);
        }

        return $this->resolve($id);
    }

    public function has($id): bool {
        return array_key_exists( $id, $this->allocator );
    }

    public function resolve($id) {
        $refl = new \ReflectionClass($id);
        $con = $refl->getConstructor();

        // If class has no constructor or constructor with no parameters
        if (!$con) {
            $this->bind($id);
            return $this->get($id);
        }

        $parameters = $con->getParameters();

        if (!$parameters) {
            $this->bind($id);
            return $this->get($id);
        }

        $dependencies = array_map(function(\ReflectionParameter $par)
        {
            $name = $par->getName();
            $type = $par->getType();

            if ( !$type ) { throw new ContainerException("Can't resolve variable {$name} because it has no type hint"); }

            if ( !$type->isBuiltin() ) {
                return $this->get( $type->getName() );
            }

        }, $parameters);

        return $refl->newInstanceArgs($dependencies);
    }
}