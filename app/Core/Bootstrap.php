<?php

namespace App\Core;

use App\Core\App;
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
        Router::handleRequest( Request::capture() );
    }

    private static function bindServices() {
        App::bind('log', function($value) {
            print_r('<pre>');
            print_r($value);
            print_r('</pre>');
        });


        App::bind('sanitizer', function($string) {
        	return htmlspecialchars( strip_tags( trim( $string ) ) );
        });

    }
}