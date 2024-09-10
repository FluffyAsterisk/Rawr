<?php

namespace App\Core;

use App\Core\App;
use App\Core\ServiceContainer;
use App\Core\View;
use App\Enums\CacheType;
use App\Helpers\Cache;
use App\Helpers\EventManager;
use App\Helpers\Loger;
use App\Helpers\Request;
use App\Helpers\Router;
use App\Helpers\Sanitizer;

class Bootstrap {
    public static function init() {
        $c = ServiceContainer::init();
        self::bindServices($c);
        self::registerEvents($c);

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

        $c->bind(EventManager::class, function(ServiceContainer $c) {
            return new EventManager();
        });

        $c->bind(\PDO::class, function(ServiceContainer $c) 
            {
                $credentials = ( $c->get(App::class) )->db_cred();

                extract($credentials);

                $dsn = sprintf("%s:dbname=%s;user=%s;password=%s;", $DB_ENGINE, $DB_NAME, $DB_USERNAME, $DB_PASSWORD);
                $dsn = isset( $DB_HOST ) ? $dsn . "host={$DB_HOST};" : $dsn;
                $dsn = isset( $DB_PORT ) ? $dsn . "port={$DB_PORT};" : $dsn;

                try {
                    $pdo = new \PDO($dsn);
                    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                    return $pdo;
                } catch (\PDOException $e) {
                    throw new \PDOException($e->getMessage(), (int) $e->getCode());
                }
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

    private static function registerEvents(ServiceContainer $c) {
        $manager = $c->get(EventManager::class);

        $manager->registerEvent('pageRendered', function($data) use ($c) {
            $loger = $c->get(Loger::class);
            $loger->setName('view');
            $loger->info("Rendering page '" . $data['pageName'] . "'...");
        });

        $manager->registerEvent('serverError', function($data) use ($c) {
            $loger = $c->get(Loger::class);
            $loger->setName('server');
            $loger->error( array_key_exists('loger_message', $data) ? $data['loger_message'] : $data['error_message'] );

			( $c->get(View::class) )->render('error', [
				'error_code' => $data['error_code'],
				'error_message' => $data['error_message'],
			]);

            die();
        });
    }
}