<?php

namespace App\Controllers;

use App\Core\Controller as BaseController;
use App\Core\View;

class MainController extends BaseController {
    public function __construct(
        private View $view, 
        private \App\Models\UserPostRepository $userPostRepository, 
        private \App\Helpers\EventManager $eventManager
    ) {}

    public function index() {
        $pageName = 'index';

        $this->view->render($pageName, [], false);
    }
}