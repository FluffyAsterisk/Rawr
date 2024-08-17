<?php

namespace App\Core;

use Psr\Container\ContainerInterface;
use App\Exceptions\ContainerException;

class ServiceContainer implements ContainerInterface {
    private static $instance;
    private static $isAutoBind = 0;
    private $allocator = [];

    public function __construct(bool $autoBind) {
        if ( isset(self::$instance) ) { throw new ContainerException("Container should be created using init method, not construct"); }
        self::$isAutoBind = $autoBind;
    }

    public static function init(bool $autoBind = false) {
        if ( !isset(self::$instance) ) {
            self::$instance = new self($autoBind);
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

    private function resolve($id) {
        $refl = new \ReflectionClass($id);
        $con = $refl->getConstructor();

        // If class has no constructor or constructor with no parameters
        if ( !$con || !$con->getParameters() ) 
        {
            if (self::$isAutoBind) {
                $this->bind($id);
                return $this->get($id);
            } else { return new $id; }
        }

        $parameters = $con->getParameters();
        $dependencies = $this->resolveDependencies($parameters);

        return $refl->newInstanceArgs($dependencies);
    }

    private function resolveDependencies(array $parameters) {
        return array_map(function(\ReflectionParameter $par)
        {
            $name = $par->getName();
            $type = $par->getType();

            if ( !$type ) { throw new ContainerException("Can't resolve variable {$name} because it has no type hint"); }
            if ( $type instanceof \ReflectionUnionType ) { throw new ContainerException("Union type classes is not supported"); }

            if ( $type == ServiceContainer::class ) {
                return $this->init();
            }
            
            if ( !$type->isBuiltin() ) {
                return $this->get( $type->getName() );
            }

            throw new ContainerException("Couldn't resolve {$name} typeof {$type}");

        }, $parameters);
    }
}