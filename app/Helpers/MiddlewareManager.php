<?php

namespace App\Helpers;
use App\Core\ServiceContainer;

class MiddlewareManager
{
    private array $middlewares = [];
    public function __construct(private ServiceContainer $container)
    {
    }

    public function chain(string $route, $method, string $middlewareClass)
    {
        array_key_exists($method, $this->middlewares) ?: $this->middlewares[$method] = [];

        $inst = $this->container->get($middlewareClass);

        if ( array_key_exists($route, $this->middlewares[$method]) ) 
        {
            $this->middlewares[$method][$route]->setNext( $inst );
        } else 
        {
            $this->middlewares[$method][$route] = $inst;
        }
    }

    public function resolve($method, $route)
    {
        if (
            !array_key_exists($method, $this->middlewares) 
            ||
            !array_key_exists($route, $this->middlewares[$method])
        ) { return true; } 
        
        return $this->middlewares[$method][$route]->handle($this->container);
    }
}