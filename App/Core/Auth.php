<?php
namespace App\Core;

use App\Config\Configuration;

/**
 * A very basic authentification component.
 */
class Auth {

    /**
     * Username.
     *
     * @var string
     */
    protected $username;

    /**
     * Password .
     *
     * @var password
     */
    protected $pass;

    public function __construct() {}

    /**
     * Retrieve username.
     *
     * @return
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * Set username.
     *
     * @param $username
     */
    public function setUsername($username) {
        $this->username = $username;
    }

    /**
     * Set password.
     *
     * @param string $pass
     */
    public function setPassword($pass) {
        $this->pass = sha1($pass);
    }

    /**
     * (Very) basic authentification.
     * TODO: Add bcrypt.
     *
     * @return void|\App\Core\Auth
     */
    public function authenticate() {
        if ((Configuration::getAppAdminUsername() && $this->username == Configuration::getAppAdminUsername()) && (Configuration::getAppAdminUsername() && $this->pass == sha1(Configuration::getAppAdminPassword()))) {
            return $this;
        } else {
            return;
        }
    }
}