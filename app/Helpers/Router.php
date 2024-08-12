<?php

namespace App\Helpers;
use App\Core\View;

class Router {
    private static $ROUTES;

    public static function get($uri, $func): void {
	self::registerRoute('GET', $uri, $func);
    }

    public static function post($uri, $func): void {
	self::registerRoute('POST', $uri, $func);
    }

    private static function registerRoute($method, $route, $func): void {
	self::$ROUTES[$method] = array($route => $func);
    }

    public static function handleRequest($request): void {
	$routes = self::$ROUTES[ $request['METHOD'] ];
	
	
	foreach($routes as $key => $value) {
	    if ($request['URI'] == $key) {
		$value();
		break;
	    }
	}

    }
}

#Router::get('forum', function() {
#    print_r('Something');
#});
