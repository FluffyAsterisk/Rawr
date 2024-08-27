<?php

namespace App\Controllers;

use App\Core\Controller as BaseController;
use App\Core\View;
use App\Models\UserMapper;
use App\Models\User;

class MainController extends BaseController {
    public function __construct(private View $view, private UserMapper $userMapper) {}

    public function index() {
        // $this->view->render('index', [], false);
        $users = $this->userMapper->selectAll();
        print_r($users);

        die();

        $user = $this->user->where('id', 3)->get();

        $user->username = 'someotherusername';
        $user->password = 'someotherpassword';
        $user->save();
        
        $users = $this->user->get();
        foreach ($users as $user) {
            print_r($user->username);
        }
        $this->uMapper->save($users);



        // $this->userMapper->save( [$user] );
    }
}