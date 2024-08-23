<?php

namespace App\Controllers;

use App\Core\Controller as BaseController;
use App\Core\View;
use App\Models\UserMapper;

class MainController extends BaseController {
    public function __construct(private View $view, private UserMapper $userMapper) {}

    public function index() {
        $this->view->render('index', [], false);
        // $users = $this->userMapper->selectAll();
        // print_r('<pre>');
        // print_r($users);
        // print_r('</pre>');
        // $this->userMapper->save( $users );
    }
}