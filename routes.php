<?php

use App\Helpers\Router;
use App\Helpers\Request;
use App\Core\View;

Router::get('/', function() {
    View::render('index');
});

Router::post('/reg', function() {
    $request = Request::capture();
    print_r($request);
});
