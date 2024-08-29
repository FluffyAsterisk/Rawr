<?php

namespace App\Controllers;

use App\Core\Controller as BaseController;
use App\Models\UserMapper;
use App\Core\View;

class MainController extends BaseController {
    public function __construct(private View $view, private UserMapper $userMapper, private \App\Helpers\QueryBuilderMapper $queryBuilder) {}

    public function index() {
        // $this->view->render('index', [], false);

        $this->queryBuilder->initMapper($this->userMapper);

        $data = [
            ['name' => 'user', 'password' => 12345, 'role' => 1],
            ['name' => 'user1', 'password' => 54321],
        ];

        // $sql = $this->queryBuilder->insert()->setTable('users')->setValues($data)->write();
        // $sql = $this->uMapper->update()->setTable('users')->setValues(['name' => 'Oleg', 'password' => 12345])->where()->equals('id', 4)->write();
        // $sql = $this->uMapper->delete()->setTable('users')->write();
        $users = $this->queryBuilder->select()->setTable('users')->columns(['*'])->where()->greaterThan('id', 3)->execute();

        print_r($users);
    }
}