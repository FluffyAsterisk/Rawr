<?php

namespace App\Controllers;

use App\Core\Controller as BaseController;
use App\Core\View;
use App\Core\App;

class MainController extends BaseController {
    public function __construct(private $pdo) {}

    public function index() {
        $pretty = App::get('prettyPrint');

        $st = $this->pdo->prepare('SELECT * FROM `users`');
        $st->execute();
        $pretty( $st->fetchAll() );

        View::render('index');
    }
}