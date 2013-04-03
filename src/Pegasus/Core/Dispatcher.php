<?php
namespace Pegasus\Core;

use Pegasus\Config\Configuration;
use Pegasus\Core\Request;
use Pegasus\Core\Response;
use Pegasus\Core\Router;
use \RedBean_Facade as Redbean;

class Dispatcher {
    /**
     * Controller to invoke.
     *
     * @var Controller
     */
    protected $controller;
    /**
     * Action to call.
     * @var string
     */
    protected $action;

    public function __construct() {}
    /**
     * Dispatches and invokes given Request, handing over control to the involved controller.
     *
     * If no controller of given name can be found, invoke() will throw an exception.
     * If the controller is found, and the action is not found an exception will be thrown.
     *
     */
    public function dispatch() {
        $request = new Request();
        $request->parseRequest();
        $request->setBaseUrl(Configuration::getBaseUrl());

        $this->_invoke($request);
    }

    /**
     * Triggers the controller and action.
     *
     * @param $request The parsed request object.
     * @return void
     */
    protected function _invoke(Request $request) {
        try {
            $mimeType = null;
            $auth = null;
            $controllerNs = 'App\Controller\\' . $request->getControllerClassName() . 'Controller';
            $controllerAction = $request->getControllerAction();
            $viewPath = APP_DIR . DIRECTORY_SEPARATOR . 'View' . DIRECTORY_SEPARATOR . $request->getControllerClassName() . DIRECTORY_SEPARATOR . $request->getControllerAction();

            // Set auth infront of anything admin route related.
            if ($request->getRouteRequiresAuth()) {
                session_start();
                if (isset($_SESSION['auth']) ) {
                    $auth = unserialize($_SESSION['auth']);
                    if (!$auth instanceof Auth) {
                        $auth = null;
                    }
                } else {
                    // Intercept request requiring Auth by serving AuthController up first.
                    $controllerNs = Configuration::getNamespace() . '\Controller\AuthController';
                    $controllerAction = 'index';
                    $viewPath = VENDOR_DIR . DIRECTORY_SEPARATOR . 'View' . DIRECTORY_SEPARATOR . 'Auth' . DIRECTORY_SEPARATOR . 'index';
                }
            }

            // Verify the controller defined by user and matched by router actually exists.
            if (class_exists($controllerNs)) {
                $controller = new $controllerNs;
                $controller->setRequest($request);

                // Verify the action defined by user and matched by router actually exists and isn't private.
                if (substr($controllerAction, 0, 1) == '_' || !method_exists($controller, $controllerAction)) {
                    // Throw exception if action not found.
                    throw new \Exception('Action error: ' .  $controllerAction . ' action not found!');
                } else {
                    // Assign the view to this matching controller and action.
                    $controllerView = new View($viewPath);
                    $controller->setView($controllerView);
                    $controller->setAuthed($auth);
                    $controller->setAdapter(new Redbean());
                    call_user_func_array(array($controller, $controllerAction), array());

                    // See if the controller has invoked any redirection after its init.
                    if ($controller->getRedirect()) {
                        $request->redirect($controller->getRedirect());
                        return;
                    }

                    // Retrieve the View from the controller, in any modified state it may be.
                    $request->setContent($controller->getView()->render());
                    $mimeType = $controller->getMimeType();
                }
            } else {
                // Throw exception if controller cannot be found.
                throw new \Exception('Controller error: ' . $controllerNs . ' does not exist!');
            }
        } catch (\Exception $e) {
            $request->setStatusMessage($e->getMessage());

            if ($e->getCode()) {
                $request->setStatusCode($e->getCode());
            } else {
                $request->setContent($e->getMessage());
            }
        }

        $request->send($mimeType);
        return;
    }
}