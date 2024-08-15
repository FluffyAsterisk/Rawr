<?php

namespace App\Controllers;

use App\Core\Controller as BaseController;
use App\Core\View;
use App\Core\App;

class MainController extends BaseController {
    public static function index() {
        View::render('index');
    }
}