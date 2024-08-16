<?php

namespace App\Core;
use App\Helpers\Router;
use App\Core\ServiceContainer;

class App {
    private static $PARAMS;
    private static $PROJECT_ROOT = __DIR__.'/../../';
    private static $VIEWS_PATH = __DIR__.'/../../resources/views/';
    private static $CACHE_PATH = __DIR__.'/../../cache/';
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

    public static function db_cred() {
        // return [
        //     'DB_ENGINE' => self::$PARAMS['DB_ENGINE'],
        //     'DB_HOST' => self::$PARAMS['DB_HOST'],
        //     'DB_NAME' => self::$PARAMS['DB_NAME'],
        //     'DB_USERNAME' => self::$PARAMS['DB_USERNAME'],
        //     'DB_PASSWORD' => self::$PARAMS['DB_PASSWORD'],
        // ];
        return self::$PARAMS;
    }

    public static function setContainer(ServiceContainer $container) {
        $c = get_class($container);

        if ( isset(self::$container) ) { throw new \Exception("Container is already set"); }

        self::$container = $container;
    }

    public static function loadConfig($config): void {
        $sanitizer = self::get('sanitizer');

        foreach ($config as $key => $value) {
            $key = $sanitizer($key);
            $value = $sanitizer($value);
            self::$PARAMS[$key] = $value;
        }
    }

    public static function bind($name, $loader) {
        self::$container->bind($name, $loader);
    }

    public static function get($name) {
        return self::$container->get($name);
    }
}
