<?php
namespace  Pegasus\Controller;

use Pegasus\Core\Auth;
use Pegasus\Core\Controller;

class AuthController extends Controller {

    protected $authed;

    public function __construct() {
        $this->setMimeType('html');
    }

    /**
     * Index action.
     */
    public function index() {
        $request = $this->getRequest();

        if (isset($_SESSION['auth'])) {
            $auth = unserialize($_SESSION['auth']);

            if ($auth instanceof Auth) {
                $this->setRedirect('/admin');
            }
        } else {
            if ($request->is('post')) {
                $credentials = $request->getParameters();

                $user = $credentials['username'];
                $pass = $credentials['password'];

                $auth = new Auth();
                $auth->setUsername($user);
                $auth->setPassword($pass);

                if ($auth->authenticate()) {
                    $_SESSION['auth'] = serialize($auth);
                    $this->setRedirect('/admin');
                } else {
                    $this->view->assign('error', 'Wrong username and/or password.');
                }
            }
        }
    }

    public function setAuthed($authed) {
        $this->authed;
    }

    public function getAuthed() {
        return $this->authed;
    }
}