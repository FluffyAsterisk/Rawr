<?php

namespace App\Core;

use App\Core\App;
use App\Helpers\Router;
use App\Helpers\Request;
use App\Core\ServiceContainer;
use App\Helpers\Sanitizer;
use App\Helpers\Cache;
use App\Enums\CacheType;

class Bootstrap {
    public static function init() {
        $c = ServiceContainer::init();
        self::bindServices($c);

        $app = $c->get(App::class);
        $app->loadConfig( $app->base_path().'.env' );
        
        self::initRouter($c, $app);
    }

    private static function initRouter(ServiceContainer $c, App $app) {
        $request = $c->get(Request::class);
        $router = $c->get(Router::class);

        self::initRoutes($router, $app->base_path().'routes.php');
        $router->handleRequest( $request->capture() );
    }

    private static function initRoutes($router, $filePath) {
        require_once $filePath;
    }

    private static function bindServices(ServiceContainer $c) {
        $c->bind(\Redis::class, function(ServiceContainer $c) {
            $credentials = ( $c->get(App::class) )->redis_cred();

            extract($credentials);

            $c = ['host' => (int) $REDIS_HOST];
            if (isset($REDIS_PORT)) { $c['port'] = (int) $REDIS_PORT; }
            if (isset($REDIS_USERNAME) && isset($REDIS_PASSWORD)) { $c['auth'] = [$REDIS_USERNAME, $REDIS_PASSWORD]; }
            
            $redis = new \Redis();

            return $redis;
        });

        $c->bind(App::class, function(ServiceContainer $c) {
            return new App( $c->get(Sanitizer::class) );
        });

        $c->bind(\PDO::class, function(ServiceContainer $c) 
            {
                $credentials = ( $c->get(App::class) )->db_cred();

                extract($credentials);

                $dsn = sprintf("%s:dbname=%s;user=%s;password=%s;", $DB_ENGINE, $DB_NAME, $DB_USERNAME, $DB_PASSWORD);
                $dsn = isset( $DB_HOST ) ? $dsn . "host={$DB_HOST};" : $dsn;
                $dsn = isset( $DB_PORT ) ? $dsn . "port={$DB_PORT};" : $dsn;

                return new \PDO($dsn);
            }
        );

        $c->bind(Cache::class, function(ServiceContainer $c) {
            $params = ( $c->get(App::class) )->cache_params();

            extract($params);

            $CACHE_ENGINE = sprintf("%sCache", strtolower($CACHE_ENGINE) );
            $CACHE_ENGINE = "\App\Caching\\" . ucfirst( $CACHE_ENGINE );

            return new Cache( $c->get($CACHE_ENGINE), $c->get(\App\Caching\BaseCacheFront::class), CacheType::fromName($CACHE_TYPE) );
        });

    }
}