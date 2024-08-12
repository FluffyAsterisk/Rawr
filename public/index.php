<?php

use App\Helpers\Request;
use App\Core\App;

$config = parse_ini_file('../.env');

require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/../routes.php';


App::loadConfig($config);
App::handleRequest(Request::capture());
