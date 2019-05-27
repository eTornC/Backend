<?php

namespace eTorn\Routes;

use eTorn\Controller\TurnManager;
use eTorn\Models\Config;
use Phroute\Phroute\RouteCollector;

class ActionsRoutes
{
    public static function manageRoutes(RouteCollector $router)
    {
        $prefix = '';

        // -----------------------------------------------------------------
        // ---------------------------- ACTIONS ----------------------------
        // -----------------------------------------------------------------

        $router->get($prefix . '/store/{idStore}/nextTurn', function ($idStore) {

            if (isset($_REQUEST['till'])) {
                $idTill = $_REQUEST['till'];
            } else {
                $idTill = null;
            }

            return (new TurnManager())->nextTurn($idStore, $idTill);
        });

        $router->get($prefix . '/store/{idStore}/actualTurn', function ($idStore) {
            return (new TurnManager())->getActualTurns($idStore);
        });

        $router->get($prefix . '/store/{idStore}/waitingTurns', function ($idStore) {
            return (new TurnManager())->waitingTurns($idStore);
        });

        $router->post($prefix . '/store/{idStore}/turn', function ($idStore) {
            $body = file_get_contents('php://input');
            return (new TurnManager())->newNormalTurn($body, $idStore);
        });

        $router->post($prefix . '/store/{idStore}/vipTurn', function ($idStore) {
            $body = file_get_contents('php://input');
            return (new TurnManager())->newVipTurn($body, $idStore);
        });

        $router->post($prefix . '/store/{idStore}/hourTurn', function ($idStore) {
            $body = file_get_contents('php://input');
            $body = json_decode($body);
            return (new TurnManager())->newHourTurn($body->hour, $idStore); // timestamp!!!
        });

        $router->post($prefix . '/clockUpdate', function () {

            $minuteInterval = (int) Config::where('key', 'MIN_DURATION_BUCKETS')->first()->value;

            if (date('i') % $minuteInterval == 0) {
                return (new TurnManager())->updateHourTurns();
            }

            return array('done' => false);
        });
    }
}