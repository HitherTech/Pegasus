<?php
namespace App\Controller;

use App\Core\View;
use App\Core\Controller;

class IndexController extends Controller {

    public function index() {
        $this->view->assign('message', rand(0,5));
    }
}