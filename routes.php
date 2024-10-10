<?php

use App\Controllers\MainController;
use App\Core\View;
use App\Helpers\Request;
use App\Middlewares\AnotherMiddleware;
use App\Middlewares\FunMiddleware;
use App\Middlewares\TestMiddleware;

// $router->get('/', function(View $view, Request $request) {
//     $view->render('index');
// });

$router->post('/registration', function(View $view, Request $request) {
    $req = $request->capture();
    print_r($req);
});

$router->get('/registration', [ MainController::class, 'registration' ])->chain(TestMiddleware::class)->chain(FunMiddleware::class)->chain(AnotherMiddleware::class);

$router->get('/', function() {
    header('Location: /dashboard');
    die();
});

$router->get('/dashboard', [ MainController::class, 'dashboard' ]);
$router->get('/tickets', [ MainController::class, 'tickets' ]);
$router->get('/history', [ MainController::class, 'history' ]);
$router->get('/personalNotebook', [ MainController::class, 'personalNotebook' ]);
$router->get('/ticket', [ MainController::class, 'ticket' ]);
$router->get('/users', [ MainController::class, 'users' ]);