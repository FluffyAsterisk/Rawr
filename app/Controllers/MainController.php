<?php

namespace App\Controllers;

use App\Core\Controller as BaseController;
use App\Core\View;

class MainController extends BaseController {
    public function __construct(private View $view, private \App\Models\UserRepository $userRepository) {}

    public function index() {
        // $this->view->render('index', [], false);

        $data = [
            ['name' => 'user', 'password' => 12345, 'role' => 1],
            ['name' => 'user1', 'password' => 54321],
        ];

        $users = $this->userRepository
        ->select()
        ->columns(['id', 'login', 'password', 'role'])
        ->innerJoin('posts', 'id', 'user_id', ['id' => 'p_id', 'user_id', 'title'])
        // ->where()
        // ->lessThan('id', 4)
        ->write();
        
        // $sql = $this->queryBuilder->insert()->setValues($data)->write();
        // $users = $this->userRepository->update()->setValues(['login' => 'Oleg', 'password' => 12345])->where()->equals('id', 4)->write();
        // $users = $this->userRepository->delete()->where()->equals('id', 3)->write();
        // $users = $this->userRepository->select()->columns(['*'])->where()->equals('id', 4)->execute();

        print_r('<pre>');
        print_r($users);
        print_r('</pre>');
    }
}