<?php

namespace App\Controllers;

use App\Core\Controller as BaseController;
use App\Core\View;

class MainController extends BaseController {
    public static function index() {
        View::render('index');
    }
}