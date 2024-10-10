<?php

namespace App\Helpers;

use App\Helpers\EventManager;
use App\Core\ServiceContainer;
use App\Exceptions\MiddlewareException;

class Router {
    private $ROUTES = [];
	private array $lastRegisteredRoute = ['route' => '', 'method' => ''];

	public function __construct(private ServiceContainer $container, private MiddlewareManager $middlewareManager, private EventManager $eventManager) {
	}

    public function get($uri, $func) {
		return $this->registerRoute('GET', $uri, $func);
    }

    public function post($uri, $func) {
		return $this->registerRoute('POST', $uri, $func);
    }

    public function delete($uri, $func) {
		return $this->registerRoute('DELETE', $uri, $func);
    }

    public function update($uri, $func) {
		return $this->registerRoute('UPDATE', $uri, $func);
    }

	public function chain(string $class) {
		$l = $this->lastRegisteredRoute;
		$this->middlewareManager->chain($l['route'], $l['method'], $class);
		return $this;
	}

    private function registerRoute($method, $route, $callback) {
		array_key_exists($method, $this->ROUTES) ?: $this->ROUTES[$method] = [];
		$this->ROUTES[$method][$route] = $callback;
		$this->lastRegisteredRoute['method'] = $method;
		$this->lastRegisteredRoute['route'] = $route;
		return $this;
    }

    public function handleRequest($request) {
		$routes = $this->ROUTES[ $request['REQUEST_METHOD'] ];
		$uri = parse_url( $request['REQUEST_URI'] )['path'];

		if ( !array_key_exists($uri, $routes) ) {
			$this->eventManager->notify('serverError', [
				'error_code' => 404,
				'error_message' => 'Page not found',
				'loger_message' => "Page not found $uri"
			]);
		}

		$callback = $routes[$uri];

		if ($callback) 
		{
			try 
			{
				$this->middlewareManager->resolve($request['REQUEST_METHOD'], $uri);
			} 
			catch (MiddlewareException $e) 
			{
				$this->eventManager->notify('serverError', [
					'error_code' => $e->getCode(),
					'error_message' => $e->getMessage(),
				]);
			}
		} 
		else 
		{
		}

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
}