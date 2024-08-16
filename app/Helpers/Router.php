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

    public function handleRequest($request): int {
		$routes = $this->ROUTES[ $request['METHOD'] ];
		$uri = parse_url( $request['URI'] )['path'];
	
		$callback = $this->resolveRoute($routes, $uri);

		if ( is_array($callback) ) {
			$controller = new $callback[0]( $this->container->get(\PDO::class), $this->container->get(\App\Core\View::class) );
			$controller->{$callback[1]}();
		} else {
			$callback($this->container->get(\App\Core\View::class), $this->container->get(\App\Helpers\Request::class));
		}

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