<?php

namespace App\Core;

use App\Core\App;
use App\Helpers\Router;
use App\Helpers\Request;
use App\Core\ServiceContainer;
use App\Helpers\Sanitizer;

class Bootstrap {
    public static function init() {
        $c = ServiceContainer::init();
        self::bindServices($c);

        $app = $c->get(App::class);
        $app->loadConfig( $app->base_path().'.env' );

        $lg = $c->get(\App\Helpers\Loger::class);
        $cB = new \App\Caching\FileCacheBack($app);

        // $cB->set('user', 'testuser', \DateInterval::createFromDateString('60 sec'));
        // $lg->prettyPrint( $cB->get('user') );
        $cB->clear();
        die();
        self::initRouter($c, $app);
    }

    private static function initRouter(ServiceContainer $c, App $app) {
        $request = $c->get(Request::class);
        $router = $c->get(Router::class);

        self::initRoutes($router, $app->base_path().'routes.php');
        $router->handleRequest( $request->capture() );
    }

    private static function initRoutes($router, $filePath) {
        require $filePath;
    }

    private static function bindServices(ServiceContainer $c) {
        $c->bind(\Redis::class, function(ServiceContainer $c) {
            $host = ( $c->get(App::class) )->redis_cred();

            $redis =  new \Redis();
            $redis->connect($host);

            return $redis;
        });

        $c->bind(App::class, function(ServiceContainer $c) {
            return new App( $c->get(Sanitizer::class) );
        });

        $c->bind(\PDO::class, function(ServiceContainer $c) 
            {
                $app = $c->get(App::class);
                $credentials = $app->db_cred();
                $t = gettype($credentials);
                if ( !is_array($credentials) ) { throw new \Exception("DB credentials should be passed as array, not {$t}"); }
                extract($credentials);

                $dsn = sprintf("%s:dbname=%s;user=%s;password=%s;", $DB_ENGINE, $DB_NAME, $DB_USERNAME, $DB_PASSWORD);
                $dsn = isset( $DB_HOST ) ? $dsn . "host={$DB_HOST};" : $dsn;
                $dsn = isset( $DB_PORT ) ? $dsn . "port={$DB_PORT};" : $dsn;

                return new \PDO($dsn);
            }
        );

        $c->bind(Cache::class, function(ServiceContainer $c) {
            return new Cache( $c->get(\App\Caching\FileCacheBack::class), $c->get(\App\Caching\BaseCacheFront::class) );
        });

    }
}