<?php

namespace App\Core;

use App\Core\App;
use App\Core\View;
use App\Helpers\Router;
use App\Helpers\Request;
use App\Core\ServiceContainer;

class Bootstrap {
    public static function init() {
        $config_path = App::base_path().'.env';

        // ServiceContainer instantiated inside a static method
        App::setContainer( ServiceContainer::init() );
        self::bindServices();

        App::loadConfig( parse_ini_file($config_path) );

        try {
	        Router::handleRequest( Request::capture() );
        } catch (\Exception $e) {
            View::render('error', ['error_code' => 404, 'error_message' => 'Page doesn\'t exist']); 
        }
    }

    private static function bindServices() {
        App::bind('prettyPrint', function() {

            return function($value) {
                print_r('<pre>');
                print_r($value);
                print_r('</pre>');
            };

        });

        App::bind('sanitizer', function() {

            return function($string) {
        	    return htmlspecialchars( strip_tags( trim( $string ) ) );
            };

        });

        App::bind('db', function(ServiceContainer $c) {
            $credentials = App::db_cred();
            $t = gettype($credentials);
            if ( !is_array($credentials) ) { throw new \Exception("DB credentials should be passed as array, not {$t}"); }
            extract($credentials);

            $dsn = sprintf("%s:dbname=%s;user=%s;password=%s;", $DB_ENGINE, $DB_NAME, $DB_USERNAME, $DB_PASSWORD);
            $dsn = isset( $DB_HOST ) ? $dsn . "host={$DB_HOST};" : $dsn;
            $dsn = isset( $DB_PORT ) ? $dsn . "port={$DB_PORT};" : $dsn;

            return new \PDO($dsn);
        } );

    }
}