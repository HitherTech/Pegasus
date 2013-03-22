<?php
namespace App\Core;

use App\Core\Request;

class Controller {
    /**
     * Request.
     *
     * @var Request
     */
    private $request;
    /**
     * Response.
     *
     * @var Response
     */
    private $response;

    /**
     * Mime type.
     *
     * @var string
     */
    private $mimeType;
    /**
     * View.
     *
     * @var View
     */
    public $view;
    /**
     * Adapter.
     *
     * @var \RedBean_Facade
     */
    private $adapter;
    /**
     * Redirect route.
     * @var string
     */
    private $redirect;

    /**
     * Render view trigger.
     * @var boolean
     */
    private $renderView = true;

    /**
     * Authed.
     * @var null|Auth.
     */
    private $authed;
    /**
     * Default constructor.
     */
    public function __construct() {}

    /**
     * Retrieve request.
     * @return Request
     */
    public function getRequest() {
        return $this->request;
    }

    /**
     * Set request.
     *
     * @param Request $request
     * @return \App\Core\Controller
     */
    public function setRequest($request) {
        $this->request = $request;
        return $this;
    }

    /**
     * Retrieves mimetype set by the controller.
     *
     * @return string
     */
    public function getMimeType() {
        return $this->mimeType;
    }

    /**
     * Set mimetype.
     *
     * @param string $mimeType
     */
    public function setMimeType($mimeType) {
        $this->mimeType = $mimeType;
    }

    /**
     * Set adapter.
     *
     * @param \RedBean_Facade $adapter
     * @return \App\Core\Controller
     */
    public function setAdapter(\RedBean_Facade $adapter) {
        $this->adapter = $adapter;
        return $this;
    }

    /**
     * Retrieve Redbean adapter.
     *
     * @return \RedBean_Facade
     */
    public function getAdapter() {
        return $this->adapter;
    }

    /**
     * Set view for the controller.
     *
     * @param View $view
     */
    public function setView(View $view) {
        if ($view instanceof View) {
            $this->view = $view;
        }
    }

    /**
     * Retrieve the view to use for response.
     *
     * @return View;
     */
    public function getView() {
        return $this->view;
    }

    /**
     * Set redirect route directive.
     *
     * @param string $route
     */
    public function setRedirect($route) {
        $this->redirect = $route;
    }

    /**
     * Retrieve redirect route directive.
     *
     * @return string
     */
    public function getRedirect() {
        return $this->redirect;
    }

    /**
     * Retrieve render view trigger.
     *
     * @param boolean $render
     */
    public function setRenderView($render) {
        $this->renderView = (boolean) $render;
    }

    /**
     * Retrieve render view trigger.
     *
     * @return boolean
     */
    public function getRenderView() {
        return $this->renderView;
    }

    /**
     * Retrieve authed trigger.
     *
     * @return null|Auth
     */
    public function getAuthed() {
        return $this->authed;
    }

    /**
     * Set authed trigger.
     *
     * @param $authed
     */
    public function setAuthed($authed) {
        $this->authed = $authed;
    }
}