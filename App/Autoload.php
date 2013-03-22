<?php
namespace App;

class Autoload {
    public static function init() {
        spl_autoload_extensions('.php');
        spl_autoload_register(function ($class) {
            $file = str_replace('\\', DIRECTORY_SEPARATOR, $class) . spl_autoload_extensions();

            if (file_exists($file))  {
                require_once($file);
            }
        });
    }
}