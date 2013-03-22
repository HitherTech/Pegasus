<?php
namespace App\Core;

use App\Core\Router;

class Request extends Response {
    /**
     * The built in detectors used with `is()`.
     *
     * @var array
     */
    protected $_detectors = array(
        'get' => array('env' => 'REQUEST_METHOD', 'value' => 'GET'),
        'post' => array('env' => 'REQUEST_METHOD', 'value' => 'POST'),
        'put' => array('env' => 'REQUEST_METHOD', 'value' => 'PUT'),
        'delete' => array('env' => 'REQUEST_METHOD', 'value' => 'DELETE'),
        'head' => array('env' => 'REQUEST_METHOD', 'value' => 'HEAD'),
        'options' => array('env' => 'REQUEST_METHOD', 'value' => 'OPTIONS'),
        'ssl' => array('env' => 'HTTPS', 'value' => 1)
    );

    /**
     * Default controller class, constant.
     */
    const CONTROLLER_CLASSNAME = 'Index';
    /**
     * Position of controller.
     *
     * @var integer
     */
    protected $controllerKey = 0;
    /**
     * Site base url.
     *
     * @var string
     */
    protected $baseUrl;
    /**
     * Current controller class name.
     *
     * @var string
     */
    protected $controllerClassName;
    /**
     * Current controller view name.
     *
     * @var string
     */
    protected $controllerView;
    /**
     * Current controller action.
     *
     * @var string
     */
    protected $controllerAction;
    /**
     * List of all parameters $_GET and $_POST.
     *
     * @var array
     */
    protected $parameters;
    /**
     * Auth trigger.
     *
     * @var boolean
     */
    protected $routeRequiresAuth;

    /**
     * Default constructor.
     */
    public function __construct() {
        // Set defaults.
        $this->controllerClassName = self::CONTROLLER_CLASSNAME;
    }

    /**
     * Retrieve controller class name.
     *
     * @return string <string, \App\Core\type>
     */
    public function getControllerClassName() {
        return $this->controllerClassName;
    }

    public function getControllerView() {
        return $this->controllerClassName . '\\' . $this->controllerView;
    }

    /**
     * Set base url.
     *
     * @param string $url
     * @return \App\Core\Request
     */
    public function setBaseUrl($url) {
        $this->baseUrl = $url;
        return $this;
    }

    /**
     * Retrieve request uri.
     *
     * @return string
     */
    public function getRequestUri() {
        if (!isset($_SERVER['REQUEST_URI'])) {
            return '';
        }

        $uri = $_SERVER['REQUEST_URI'];
        $uri = trim(str_replace($this->baseUrl, '', $uri), '/');

        return $uri;
    }

    /**
     * Retrieves request method as specified in HTTP request.
     *
     * @return void|string
     */
    public function getRequestMethod() {
        if (!isset($_SERVER['REQUEST_METHOD'])) {
            return;
        }

        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Retrieve host.
     *
     * @return void|string
     */
    public function getHost() {
        if (!isset($_SERVER['HTTP_HOST'])) {
            return;
        }

        return $_SERVER['HTTP_HOST'];
    }

    /**
     * Retrieve controller action.
     *
     * @return string
     */
    public function getControllerAction() {
        return $this->controllerAction;
    }

    /**
     * Set route requires auth trigger.
     *
     * @param boolean $auth
     */
    public function setRouteRequiresAuth($auth) {
        $this->routeRequiresAuth = (boolean) $auth;
    }

    /**
     * Retrieve route requires auth trigger.
     *
     * @return boolean
     */
    public function getRouteRequiresAuth() {
        return $this->routeRequiresAuth;
    }

    /**
     * Word seperator is '-' convert the string from dash seperator to camel case.
     *
     * @param string $unformatted
     * @return string
     */
    protected function formatControllerName($unformatted) {
        if (strpos($unformatted, '-') !== false) {
            $formattedName = array_map('ucwords', explode('-', $unformatted));
            $formattedName = join('', $formattedName);
        } else {
            // String is one word.
            $formattedName = ucwords($unformatted);
        }

        // If the string starts with number.
        if (is_numeric(substr($formattedName, 0, 1))) {
            $part = $part == $this->controllerkey ? 'controller' : 'action';
            throw new \Exception('Incorrect ' . $part . ' name "' . $formattedName . '".');
        }
        return ltrim($formattedName, '_');
    }

    /**
     * Parses request and delegates GET and POST data to invokeable.
     *
     * @return \App\Core\Request
     */
    public function parseRequest() {
        $uriParts = explode('/', $this->getRequestUri());

        $router = new Router();
        $routerMatch = $router->parseRoute($uriParts);

        $params = array();
        // Wether we are in index page.
        if (!isset($uriParts[$this->controllerKey])) {
            return $this;
        }

        // Format the controller class name.
        $this->controllerClassName = $this->formatControllerName($routerMatch['controller']);
        $this->controllerAction = $routerMatch['action'];
        $this->controllerView = $routerMatch['action'];

        // Remove controller name from uri only if the router did find a match.
        if ($routerMatch['matched']) {
            unset($uriParts[$this->controllerKey]);
        }

        // Set auth trigger.
        if (isset($routerMatch['auth']) && $routerMatch['auth']) {
            $this->setRouteRequiresAuth($routerMatch['auth']);
        }

        // Find the action index in request and remove it if found.
        $actionIndex = array_search($routerMatch['action'], $uriParts);

        if ($actionIndex !== false) {
            unset($uriParts[$actionIndex]);
            reset($uriParts);
        }

        // Find and setup parameters starting from $_GET to $_POST.
        $i = 0;
        $keyName = '';
        foreach ($uriParts as $key => $value) {
            if ($i == 0) {
                $params[$value] = '';
                $keyName = $value;
                $i = 1;
            } else {
                $params[$keyName] = $value;
                $i = 0;
            }
        }

        // Add $_POST data if present.
        if ($_POST) {
            foreach ($_POST as $postKey => $postData) {
                $params[$postKey] = $postData;
            }
        }

        $this->setParameters($params);

        return $this;
    }

    /**
     * Set parameters.
     *
     * @param array $params
     * @return \App\Core\Request
     */
    public function setParameters($params) {
        $this->parameters = $params;
        return $this;
    }

    /**
     * Retrieve parameters.
     * @return void|array
     */
    public function getParameters() {
        if ($this->parameters == null) {
            $this->parameters = array();
        }
        return $this->parameters;
    }


    /**
     * Get value of $_GET or $_POST. $_POST override the same parameter in $_GET.
     *
     * @param string $name
     * @param null|string $default
     * @return null|string
     */
    public function getParameter($name, $default = null) {
        if (isset($this->parameters[$name])) {
            return $this->parameters[$name];
        }
        return $default;
    }


    /**
     * Check whether or not a Request is a certain type.  Uses the built in detection rules.
     *
     * @param string $type The type of request you want to check.
     * @return boolean Whether or not the request is the type you are checking.
     */
    public function is($type) {
        $type = strtolower($type);
        if (!isset($this->_detectors[$type])) {
            return false;
        }

        $detect = $this->_detectors[$type];

        if (isset($detect['env'])) {
            if (isset($detect['value'])) {
                return ($_SERVER[$detect['env']]) == $detect['value'];
            }
        }
        return false;
    }
}