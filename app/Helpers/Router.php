<?php

namespace App\Helpers;

use App\Core\ServiceContainer;

class Router {
    private $ROUTES = array();

	public function __construct(private ServiceContainer $container) {}

    public function get($uri, $func): void {
	$this->registerRoute('GET', $uri, $func);
    }

    public function post($uri, $func): void {
	$this->registerRoute('POST', $uri, $func);
    }

    public function delete($uri, $func): void {
	$this->registerRoute('DELETE', $uri, $func);
    }

    public function update($uri, $func): void {
	$this->registerRoute('UPDATE', $uri, $func);
    }

    private function registerRoute($method, $route, $callback): void {
		array_key_exists($method, $this->ROUTES) ?: $this->ROUTES[$method] = array();
		$this->ROUTES[$method][$route] = $callback;
    }

    public function handleRequest($request) {
		$routes = $this->ROUTES[ $request['METHOD'] ];
		$uri = parse_url( $request['URI'] )['path'];
	
		$callback = $this->resolveRoute($routes, $uri);

		if ( is_array($callback) ) 
		{
			$controller = $this->container->get($callback[0]);
			$controller->{$callback[1]}();
		} 
		else 
		{
			$this->resolveCallbackFunction($callback);
		}

    }

	private function resolveCallbackFunction($callback) {
		$reflection = new \ReflectionFunction($callback);
		$parameters = $reflection->getParameters();
		
		$dependencies = array_map(function (\ReflectionParameter $par) {
			$name = $par->getName();
			$type = $par->getType();

			if (!$type) { throw new \Exception("Route callback function parameter {$name} has no typehint"); }

			if ( !$type->isBuiltin() ) {
				return $this->container->get( $type->getName() );
			}
			

		}, $parameters);

		$reflection->invokeArgs($dependencies);
	}

	private function resolveRoute($routes, $uri) {
		foreach($routes as $key => $value) 
		{
			if ($uri == $key) 
			{
				return $value;
			}
		}

		throw new \Exception("Route {$request['URI']} doesn't exist");
	}

}