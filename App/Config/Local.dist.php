<?php
namespace App\Config;

class Local {
    public static function config() {
        return array(
            'db' => array(
                'host' => '',
                'type' => '',
                'name' => '',
                'username' => '',
                'password' => '',
            ),
            'routes' => array(
                'index' => array(
                    'controller' => 'index',
                    'action' => 'index',
                ),
                'admin' => array(
                    'controller' => 'admin',
                    'action' => 'index',
                    'auth' => 1
                )
            ),
            'app' => array(
                'baseUrl' => '',
                'adminUser' => '',
                'adminPass' => ''
            )
        );
    }
}