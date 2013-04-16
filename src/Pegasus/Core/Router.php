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
            // There exists at least a controller and action in the incoming route.
            if (count($inboundRoute) > 2) {
                // Bind detailed extended routes by only the controller and action.
                $segmentedRoute = $inboundRoute[0] . '/' . $inboundRoute[1];
            } else {
                // Aliasing means no defined action. Default it to index.
                $segmentedRoute = join('/', $inboundRoute);
            }

            if ($segmentedRoute == $routeName) {
                $returnRoute = $route;
                $returnRoute['matched'] = 1;
                break;
            }
        }

        if (!key_exists('controller', $returnRoute) || !key_exists('action', $returnRoute)) {
            // Ensure the route has needed attributes.
            $returnRoute['controller'] = $inboundRoute[0];
            $returnRoute['action'] = $inboundRoute[1];
        }

        return $returnRoute;
    }
}