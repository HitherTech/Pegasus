<?php
namespace App\Core;

use App\Config\Configuration;

class Router {
    /**
     * Parse route pattern.
     *
     * @param string $route
     * @return array
     */
    public function parseRoute($inboundRoute) {
        $routes = Configuration::getRoutes();
        $returnRoute = $routes['default'];
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

        return $returnRoute;
    }
}