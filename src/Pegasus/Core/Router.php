<?php
namespace Pegasus\Core;

use Pegasus\Config\Configuration;

class Router {
    /**
     * Parse route pattern.
     *
     * @param string $route
     * @return array
     */
    public function parseRoute($inboundRoute) {
        $routes = Configuration::getRoutes();
        $returnRoute['matched'] = null;
        $returnRoute['auth'] = null;

        foreach ($routes as $routeName => $route) {
            // Bind detailed extended routes by only the controller and action.
            if (count($inboundRoute) > 2) {
                $segmentedRoute = $inboundRoute[0] . '/' . $inboundRoute[1];
                // Otherwise join the elements.
            } else {
                $segmentedRoute = join('/', $inboundRoute);
            }

            if ($segmentedRoute == $routeName) {
                $returnRoute = $route;
                $returnRoute['matched'] = 1;
                break;
            }
        }

        if (!key_exists('controller', $route) || !key_exists('action', $route)) {
            // Ensure the route has needed attributes.
            $returnRoute['controller'] = $inboundRoute[0];
            $returnRoute['action'] = $inboundRoute[1];
        }

        return $returnRoute;
    }
}