<?php
namespace Pegasus\Config;

class Configuration {
    const appNamespaceDefinition = 'Pegasus';
    private static $dbHost;
    private static $dbType;
    private static $dbName;
    private static $dbUsername;
    private static $dbPassword;
    private static $appBaseUrl;
    private static $appAdminUser;
    private static $appAdminPass;
    private static $routes;
    /**
     * Retrieve DSN String
     *
     * @return array
     */
    public static function getDsn() {
        return self::getDbType() . ':host=' . self::getHost() . ';dbname=' . self::getDatabase();
    }

    /**
     * Initiate configuration.
     *
     * @param array $localConfig
     */
    public static function init($localConfig) {
        $local = $localConfig;

        // Database settings.
        self::$dbHost = $local['db']['host'];
        self::$dbType = $local['db']['type'];
        self::$dbName = $local['db']['name'];
        self::$dbUsername = $local['db']['username'];
        self::$dbPassword = $local['db']['password'];
        // Routes.
        self::$routes = $local['routes'];
        // Username to access the admin section.
        self::$appAdminUser = $local['app']['adminUser'];
        // Password to access the admin section.
        self::$appAdminPass = $local['app']['adminPass'];
        // Application base url.
        self::$appBaseUrl = $local['app']['baseUrl'];
    }

    public static function getHost() {
        return self::$dbHost;
    }

    public static function getDbType() {
        return self::$dbType;
    }

    public static function getDatabase() {
        return self::$dbName;
    }

    public static function getUserName() {
        return self::$dbUsername;
    }

    public static function getPassword() {
        return self::$dbPassword;
    }

    public static function getBaseUrl() {
        return self::$appBaseUrl;
    }

    public static function getNameSpace() {
        return self::appNamespaceDefinition;
    }

    public static function getRoutes() {
        return self::$routes;
    }

    public static function getAppAdminUsername() {
        return self::$appAdminUser;
    }

    public static function getAppAdminPassword() {
        return self::$appAdminPass;
    }
}