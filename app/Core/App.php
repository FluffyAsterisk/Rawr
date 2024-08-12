<?php

namespace App\Core;
use App\Helpers\Router;

class App {
    public static $PARAMS;
    public static $PROJECT_ROOT = __DIR__.'/../../';
    public static $VIEWS_PATH = __DIR__.'/../../resources/views/';
    public static $CACHE_PATH = __DIR__.'/../../resources/views/cache/';

    public static function handleRequest($request): void {
	    Router::handleRequest($request);
    }

    public static function loadConfig($config): void {
	foreach ($config as $key => $value) {
	    $key = self::sanitizeParam($key);
	    $value = self::sanitizeParam($value);
	    self::$PARAMS[$key] = $value;
	}
    }

    private static function sanitizeParam($string): string {
	return htmlspecialchars( strip_tags( trim( $string ) ) );
    }

    public static function log($value): void {
        print_r('<pre>');
        print_r($value);
        print_r('</pre>');
    }
}
