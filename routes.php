<?php

use App\Controllers\MainController;

$router->get('/', function($view, $request) {
    $view->render('index');
});

$router->post('/reg', function($view, $request) {
    $req = $request->capture();
    print_r($req);
});

$router->get('/control', [ MainController::class, 'index' ]);
