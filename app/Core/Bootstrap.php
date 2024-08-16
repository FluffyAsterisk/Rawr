<?php

namespace App\Core;

use App\Core\App;
use App\Core\View;
use App\Helpers\Router;
use App\Helpers\Request;
use App\Helpers\Template;
use App\Core\ServiceContainer;

class Bootstrap {
    public static function init() {
        $c = ServiceContainer::init();
        self::bindServices($c);

        $app = $c->get(App::class);
        $config_path = $app->base_path().'.env';

        $app->loadConfig( $config_path );
        $request = $c->get(Request::class);

        self::initRoutes($c, $app->base_path().'routes.php');

        ( $c->get(Router::class) )->handleRequest( ( $c->get(Request::class) )->capture() );
    }

    private static function initRoutes(ServiceContainer $c, $filePath) {
        $router = $c->get(Router::class);

        require $filePath;
    }

    private static function bindServices(ServiceContainer $c) {
        $c->bind(Template::class, function(ServiceContainer $c) {
            return new Template( $c->get(App::class) );
        });

        $c->bind(Router::class, function(ServiceContainer $c) {
            return new Router($c);
        });

        $c->bind(View::class, function(ServiceContainer $c) {
            return new View( $c->get(Template::class) );
        });

        $c->bind(\PDO::class, function(ServiceContainer $c) 
            {
                $credentials = ( $c->get(App::class) )->db_cred();
                $t = gettype($credentials);
                if ( !is_array($credentials) ) { throw new \Exception("DB credentials should be passed as array, not {$t}"); }
                extract($credentials);

                $dsn = sprintf("%s:dbname=%s;user=%s;password=%s;", $DB_ENGINE, $DB_NAME, $DB_USERNAME, $DB_PASSWORD);
                $dsn = isset( $DB_HOST ) ? $dsn . "host={$DB_HOST};" : $dsn;
                $dsn = isset( $DB_PORT ) ? $dsn . "port={$DB_PORT};" : $dsn;

                return new \PDO($dsn);
            }
        );

    }
}