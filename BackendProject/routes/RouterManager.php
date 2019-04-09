<?php

namespace eTorn\Routes;

use eTorn\Controller\StoreManager;
use eTorn\Controller\QueueManager;
use eTorn\Controller\TurnManager;
use eTorn\Controller\ConfigManager;
use eTorn\Controller\LayoutManager;

use eTorn\Models\Config;
use Phroute\Phroute\RouteCollector;
use eTorn\Bbdd\ConfigDao;

use eTorn\Constants\ConstantsDB;
use eTorn\Controller\Logger;

class RouterManager 
{
    public static function manageRoutes(RouteCollector $router) 
    {
        $prefix = '';

        // -----------------------------------------------------------------
        // ---------------------------- DEPLOY -----------------------------
        // -----------------------------------------------------------------

        $router->any($prefix . '/deploy', function () {

            $user = ConstantsDB::DB_USER;
            $password = ConstantsDB::DB_PASSWD;
            $host = ConstantsDB::DB_SERVER;

            $script_path = \getcwd() . '/scripts/etorn.sql';

            $command = "mysql --user={$user} --password='{$password}' "
                . "-h {$host} < {$script_path}";

            $output = shell_exec($command);

            return 'done ' . $output;
        });

        // -----------------------------------------------------------------
        // ---------------------------- STORES -----------------------------
        // -----------------------------------------------------------------

        $router->get($prefix . '/stores', function () {
            return (new StoreManager())->findAll();
        });

        $router->get($prefix . '/store/{id}', function ($id) {
            return (new StoreManager())->findById($id);
        });

        $router->post($prefix . '/store', function () {
            // Form data for file upload too
            return (new StoreManager())->save($_POST['name'], $_FILES['photoStore']);
        });

        $router->put($prefix . '/store/{id}', function ($id) {
            $body = file_get_contents('php://input');
            return (new StoreManager())->update(json_decode($body), $id);
        });

        $router->delete($prefix . '/store/{id}', function ($id) {
            return (new StoreManager())->delete($id);
        });

        // -----------------------------------------------------------------
        // ---------------------------- ACTIONS ----------------------------
        // -----------------------------------------------------------------

        $router->post($prefix . '/store/{idStore}/nextTurn', function ($idStore) {
            return (new TurnManager())->nextTurn($idStore);
        });

        $router->get($prefix . '/store/{idStore}/actualTurn', function ($idStore) {
            return (new TurnManager())->getActualTurn($idStore);
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

        // -----------------------------------------------------------------
        // ---------------------------- LAYOUTS ----------------------------
        // -----------------------------------------------------------------

        $router->get($prefix . '/layouts', function () {
            return (new LayoutManager())->findAll();
        });

        $router->get($prefix . "/layout/{id}", function ($id) {
            return (new LayoutManager())->findById($id);
        });

        $router->get($prefix . '/turns-screens', function () {
            return (new LayoutManager())->findAllTurnsScreen();
        });
        
        $router->get($prefix . '/totem-screens', function () {
            return (new LayoutManager())->findAllTotemScreen();
        });

        $router->post($prefix . '/turns-screen', function () {
            $body = file_get_contents('php://input');
            return (new LayoutManager())->save(\json_decode($body), 'TURNSCREEN');
        });
        
        $router->post($prefix . '/totem-screen', function () {
            $body = file_get_contents('php://input');
            return (new LayoutManager())->save(\json_decode($body), 'TOTEMSCREEN');
        });
    }
}
