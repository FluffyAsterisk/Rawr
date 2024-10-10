<?php

namespace App\Controllers;

use App\Core\Controller as BaseController;
use App\Core\View;

class MainController extends BaseController {
    public function __construct(
        private View $view, 
        private \App\Helpers\Request $request,
        private \App\Models\UserPostRepository $userPostRepository, 
        private \App\Helpers\EventManager $eventManager,
    ) {}

    public function dashboard() {
        $this->view->render('dashboard', [
            'title' => 'dashboard',
            'uri' => $this->request->getPath(),
        ]);
    }

    public function createTicket() {
        $this->view->render('dashboard', [
            'title' => 'Create Ticket',
            'uri' => $this->request->getPath(),
        ]);
    }
    
    public function tickets() {
        $this->view->render('tickets', [
            'title' => 'Tickets',
            'uri' => $this->request->getPath(),
        ]);
    }

    public function history() {
        $this->view->render('dashboard', [
            'title' => 'history',
            'uri' => $this->request->getPath(),
        ]);
    }

    public function personalNotebook() {
        $this->view->render('dashboard', [
            'title' => 'Personal Notebook',
            'uri' => $this->request->getPath(),
        ]);
    }

    public function registration() {
        $this->view->render('registration', [
            'title' => 'Registration',
            'uri' => $this->request->getPath(),
        ]);
    }

    public function ticket()
    {
        $this->view->render('ticket', [
            'title' => 'Ticket',
            'uri' => $this->request->getPath(),
        ]);
    }

    public function users()
    {
        $this->view->render('users', [
            'title' => 'Users',
            'uri' => $this->request->getPath(),
        ]);
    }
}