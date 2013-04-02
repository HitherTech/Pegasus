<?php
namespace Pegasus\Core;

use Pegasus\Core\View;

class Response {
    /**
     * Buffer list of headers
     *
     * @var array
     */
    protected $_headers = array();
    /**
     * Holds HTTP response statuses
     *
     * @var array
     */
    protected $_statusCodes = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Large',
        415 => 'Unsupported Media Type',
        416 => 'Requested range not satisfiable',
        417 => 'Expectation Failed',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out'
    );

    /**
     * Status code.
     *
     * @var int
     */
    protected $_statusCode;
    /**
     * Content for buffer output.
     *
     * @var string
     */
    protected $_content;
    /**
     * Status message.
     *
     * @var string
     */
    protected $_statusMessage = null;
    /**
     * Reponse types.
     *
     * @var array
     */
    protected $_mimeTypes = array(
        'json' => 'application/json',
        'html' => 'text/html',
    );

    protected $_mimeType = 'html';

    /**
     * Sends the complete response to the client including headers and message body.
     * Will echo out the content in the response body.
     *
     * @return void
     */
    public function send($mimeTypeShortened) {
        if (!$mimeTypeShortened) {
            $mimeTypeShortened = $this->_mimeType;
        }

        if (!$this->getStatusMessage()) {
            $this->setStatusMessage($this->_statusCodes[$this->getStatusCode()]);
        }


        $mimeType = $this->_mimeTypes[$mimeTypeShortened];

        header('HTTP/1.1 ' . $this->getStatusCode() . $this->_statusCode[$this->getStatusCode()]);
        header('Content-type: ' . $mimeType . '; charset=utf-8');

        echo $this->_constructResponse($mimeTypeShortened);
        return;
    }

    /**
     * Adds redirection support.
     *
     * @param string $route
     */
    public function redirect($route) {
        header('Location: ' . $route);
        return;
    }

    /**
     * Set status code.
     *
     * @param integer $code
     */
    public function setStatusCode($code) {
        $this->_statusCode = $code;
    }

    /**
     * Retrieve status code.
     *
     * @return number
     */
    public function getStatusCode() {
        return $this->_statusCode;
    }

    /**
     * Set content for output.
     *
     * @param string $content
     */
    public function setContent($content) {
        $this->_content = $content;
    }

    /**
     * Retrieve content.
     *
     * @return string
     */
    public function getContent() {
        return $this->_content;
    }

    /**
     * Set overriding status message. Warning: Setting wrong message can break the RESTful philosophy.
     *
     * @param string $message
     */
    public function setStatusMessage($statusMessage) {
        $this->_statusMessage = $statusMessage;
    }

    /**
     * Retrieve status message.
     *
     * @return string
     */
    public function getStatusMessage() {
        return $this->_statusMessage;
    }

    /**
     * Setup default json representation response notation.
     *
     * @return string
     */
    private function _constructResponse($mimeType) {
        if ($this->getContent() == null) {
            $this->setContent($this->_statusCodes[$this->getStatusCode()]);
        }

        switch ($mimeType) {
            default:
            case 'json':
                $jsonResponseArray = array(
                    'success' => ($this->getStatusCode() == 200),
                    'code' => $this->getStatusCode(),
                    'message' => $this->getContent()
                );
                return json_encode($jsonResponseArray);

                break;
            case 'html':
                return $this->getContent();
                break;
        }
    }

    /**
     * Sets the response type.
     *
     * @param string $type
     */
    public function setMimeType($type) {
        $type = mb_strtolower($type, 'utf-8');
        if (key_exists($type, $this->_mimeTypes)) {
            $this->_mimeType = $type;
        }
    }
}