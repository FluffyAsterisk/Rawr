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

    private static function registerRoute($method, $route, $callback): void {
		array_key_exists($method, self::$ROUTES) ?: self::$ROUTES[$method] = array();
		self::$ROUTES[$method][$route] = $callback;
    }

    public static function handleRequest($request): int {
		$routes = self::$ROUTES[ $request['METHOD'] ];
	
		foreach($routes as $key => $value) {
			if ($request['URI'] == $key) {
				is_array($value) ? call_user_func($value) : $value();
				return 0;
			}

		}

		throw new \Exception('Route doesn\'t exist');

    }

}