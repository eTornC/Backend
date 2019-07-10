<?php


namespace eTorn\Routes;

use eTorn\Controller\DashboardManager;
use Phroute\Phroute\RouteCollector;

class DashboardRoutes
{
    public static function manageRoutes(RouteCollector $router)
    {
        $prefix = '';

        $router->get($prefix . '/layouts', function () {
            return (new DashboardManager());
        });
    }
}