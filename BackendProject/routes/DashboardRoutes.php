<?php


namespace eTorn\Routes;

use eTorn\Controller\DashboardManager;
use Phroute\Phroute\RouteCollector;

class DashboardRoutes
{
    public static function manageRoutes(RouteCollector $router)
    {
        $prefix = '';

        $router->get($prefix . '/turns-per-type', function () {
            $startDate = $_GET['start_date'];
            $endDate = $_GET['end_date'];
            $store = $_GET['store'];

            return (new DashboardManager())->turnsPerType($startDate, $endDate, $store);
        });

        $router->get($prefix . '/turns-by-day', function () {
            $startDate = $_GET['start_date'];
            $endDate = $_GET['end_date'];
            $store = $_GET['store'];

            return (new DashboardManager())->turnsByDay($startDate, $endDate, $store);
        });

        $router->get($prefix . '/turns-number', function () {
            $startDate = $_GET['start_date'];
            $endDate = $_GET['end_date'];
            $store = $_GET['store'];

            return (new DashboardManager())->turnsNumber($startDate, $endDate, $store);
        });
    }
}