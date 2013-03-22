<?php
namespace App\Controller;

use App\Core\View;
use App\Core\Controller;

class AdminController extends Controller {

    public function index() {
        if ($this->getAuthed()) {
            $this->view->assign('message', 'Welcome to admin');
        } else {
            $this->view->assign('message', 'Not logged in');
        }
    }
}