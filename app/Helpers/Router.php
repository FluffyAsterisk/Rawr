<?php

namespace App\Helpers;

use App\Core\App;

class Router {
    private static $ROUTES = array();

    public static function get($uri, $func): void {
	self::registerRoute('GET', $uri, $func);
    }

    public static function post($uri, $func): void {
	self::registerRoute('POST', $uri, $func);
    }

    public static function delete($uri, $func): void {
	self::registerRoute('DELETE', $uri, $func);
    }

    public static function update($uri, $func): void {
	self::registerRoute('UPDATE', $uri, $func);
    }

    private static function registerRoute($method, $route, $callback): void {
		array_key_exists($method, self::$ROUTES) ?: self::$ROUTES[$method] = array();
		self::$ROUTES[$method][$route] = $callback;
    }

    public static function handleRequest($request): int {
		$routes = self::$ROUTES[ $request['METHOD'] ];
		$uri = parse_url( $request['URI'] )['path'];
	
		$callback = self::resolveRoute($routes, $uri);

		if ( is_array($callback) ) {
			$controller = new $callback[0]( App::get('db') );
			$controller->{$callback[1]}();
		} else {
			$callback();
		}

		throw new \Exception("Route {$request['URI']} doesn't exist");

    }

	private static function resolveRoute($routes, $uri) {
		foreach($routes as $key => $value) {

			if ($uri == $key) 
			{
				return $value;
			}

		}

	}

}