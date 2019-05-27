<?php

namespace eTorn\Routes;

use eTorn\Controller\ConfigManager;
use Phroute\Phroute\RouteCollector;

class ConfigRoutes
{
    public static function manageRoutes(RouteCollector $router)
    {
        $prefix = '';

        // -----------------------------------------------------------------
        // ---------------------------- CONFIG -----------------------------
        // -----------------------------------------------------------------

        $router->get($prefix . '/configs', function () {
            return (new ConfigManager())->findAll();
        });

        $router->post($prefix . '/configs', function () {
            $body = file_get_contents('php://input');
            $body = \json_decode($body);
            return (new ConfigManager())->updateConfigs($body);
        });

    }
}

