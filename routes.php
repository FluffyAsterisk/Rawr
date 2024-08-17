<?php

use App\Controllers\MainController;
use App\Core\View;
use App\Helpers\Request;

$router->get('/', function(View $view, Request $request) {
    $view->render('index');
});

$router->post('/reg', function(View $view, Request $request) {
    $req = $request->capture();
    print_r($req);
});

$router->get('/control', [ MainController::class, 'index' ]);
