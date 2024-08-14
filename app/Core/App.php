<?php

namespace App\Core;
use App\Helpers\Router;
use App\Core\View;

class App {
    private static $PARAMS;
    public static $PROJECT_ROOT = __DIR__.'/../../';
    public static $VIEWS_PATH = __DIR__.'/../../resources/views/';
    public static $CACHE_PATH = __DIR__.'/../../resources/views/cache/';
    public static $dbCnct;

    public static function handleRequest($request): void {
        //self::initDb();
        try {
	        Router::handleRequest($request);
        } catch (\Exception $e) {
            View::render('404');            
        }
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

    private static function initDb() {
        $params = self::$PARAMS;
        try {
            self::$dbCnct = new PDO( sprintf('%s:dbname=%s;host=%s', $params['DB_ENGINE'], $params['DB_HOST']), $params['DB_USERNAME'], $params['DB_PASSWORD'] );
        } catch (PDOException $e) {
            echo "Connection failed " . $e->getMessage();
            die();
        }
    }

    public static function log($value): void {
        print_r('<pre>');
        print_r($value);
        print_r('</pre>');
    }
}
