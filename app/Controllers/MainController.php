<?php

namespace App\Controllers;

use App\Core\Controller as BaseController;
use App\Core\View;

class MainController extends BaseController {
    public function __construct(private View $view, private \App\Models\UserPostRepository $userPostRepository) {}

    public function index() {
        $this->view->render('index', [], false);
    }
}