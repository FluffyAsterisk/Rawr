<?php

use App\Helpers\Router;
use App\Helpers\Request;
use App\Core\View;
use App\Controllers\MainController;

Router::get('/', function() {
    View::render('index');
});

Router::post('/reg', function() {
    $request = Request::capture();
    print_r($request);
});

Router::get('/control', [ MainController::class, 'index' ]);
