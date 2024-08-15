<?php

namespace App\Core;
use App\Helpers\Router;
use App\Core\View;
use App\Core\ServiceContainer;

class App {
    private static $PARAMS;
    private static $PROJECT_ROOT = __DIR__.'/../../';
    private static $VIEWS_PATH = __DIR__.'/../../resources/views/';
    private static $CACHE_PATH = __DIR__.'/../../cache';
    private static $container;

    public static function base_path() {
        return self::$PROJECT_ROOT;
    }

    public static function views_path() {
        return self::$VIEWS_PATH;
    }

    public static function cache_path() {
        return self::$CACHE_PATH;
    }

    public static function setContainer($container) {
        $c = get_class($container);

        is_a($container, ServiceContainer::class) ?? throw new \Exception("Container should have class of ServiceContainer, not {$c}");
        if ( isset(self::$container) ) { throw new \Exception("Container is already set"); }

        self::$container = $container;
    }

    public static function handleRequest($request): void {
        try {
	        Router::handleRequest($request);
        } catch (\Exception $e) {
            View::render('error', ['error_code' => 404, 'error_message' => 'Page doesn\'t exist']);            
        }
    }

    public static function loadConfig($config): void {
        $sanitizer = self::get('sanitizer');

        foreach ($config as $key => $value) {
            $key = $sanitizer($key);
            $value = $sanitizer($value);
            self::$PARAMS[$key] = $value;
        }
    }

    public static function bind($name, $fn) {
        self::$container->bind($name, $fn);
    }

    public static function get($name) {
        return self::$container->get($name);
    }
}
