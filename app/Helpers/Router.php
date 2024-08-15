<?php

namespace App\Helpers;
use App\Core\View;

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
	
		foreach($routes as $key => $value) {
			$uri = parse_url( $request['URI'] )['path'];
			if ($uri == $key) {
				is_array($value) ? call_user_func($value) : $value();
				return 0;
			}

		}

		throw new \Exception("Route {$request['URI']} doesn't exist");

    }

}