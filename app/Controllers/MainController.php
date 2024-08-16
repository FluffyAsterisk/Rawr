<?php

namespace App\Controllers;

use App\Core\Controller as BaseController;

class MainController extends BaseController {
    public function __construct(private $pdo, private $view) {}

    public function index() {
        $st = $this->pdo->prepare('SELECT * FROM `users`');
        $st->execute();

        $this->view->render('index');
    }
}