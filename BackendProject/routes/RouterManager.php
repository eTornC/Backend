<?php

namespace eTorn\Routes;

use eTorn\Controller\StoreManager;
use eTorn\Controller\QueueManager;
use eTorn\Controller\TurnManager;
use eTorn\Controller\ConfigManager;

use Phroute\Phroute\RouteCollector;
use eTorn\Bbdd\ConfigDao;

class RouterManager {

    public static function manageRoutes(RouteCollector $router) {

        $prefix = '';

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
            //$body = file_get_contents('php://input');
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
        // ---------------------------- QUEUES -----------------------------
        // -----------------------------------------------------------------

        $router->get($prefix . '/store/{idStore}/queues', function ($idStore) {
            return (new QueueManager())->findAll($idStore);
        });

        $router->get($prefix . '/store/{idStore}/queue/{idQueue}', function ($idStore, $idQueue) {
            return (new QueueManager())->findById($idStore, $idQueue);
        });

        $router->post($prefix . '/store/{idStore}/queue', function ($idStore) {
            $body = file_get_contents('php://input');
            return (new QueueManager())->save(json_decode($body), $idStore);
        });

        $router->put($prefix . '/store/{idStore}/queue/{idQueue}', function ($idStore, $idQueue) {
            $body = file_get_contents('php://input');
            return (new QueueManager())->update(json_decode($body), $idStore, $idQueue);
        });

        $router->delete($prefix . '/store/{idStore}/queue/{idQueue}', function ($idStore, $idQueue) {
            return (new QueueManager())->delete($idStore, $idQueue);
        });

        // -----------------------------------------------------------------
        // ---------------------------- TURNS OF QUEUE ---------------------
        // -----------------------------------------------------------------

        $router->get($prefix . '/store/{idStore}/queue/{idQueue}/turns', function ($idStore, $idQueue) {
            return (new TurnManager())->findByIdStoreAndIdQueue($idStore, $idQueue);
        });

        $router->get($prefix . '/store/{idStore}/turns', function ($idStore) {
            return (new TurnManager())->waitingTurns($idStore);
        });

        $router->get($prefix . '/turn/{idTurn}', function ($idTurn) {
            return (new TurnManager())->findById($idTurn);
        });

        $router->get($prefix . '/turns', function () {
            return (new TurnManager())->findAll();
        });

        $router->get($prefix . '/store/{idStore}/queue/{idQueue}/turn/{idTurn}', function ($idStore, $idQueue, $idTurn) {
            return (new TurnManager())->findById($idTurn);
        });

        $router->post($prefix . '/store/{idStore}/queue/{idQueue}/turn', function ($idStore, $idQueue) {
            $body = file_get_contents('php://input');
            return (new TurnManager())->save($body, $idStore, $idQueue);
        });

        $router->put($prefix . '/store/{idStore}/queue/{idQueue}/turn/{idTurn}', function ($idStore, $idQueue, $idTurn) {
            $body = file_get_contents('php://input');
            return (new TurnManager())->update($body, $idStore, $idQueue, $idTurn);
        });

        $router->delete($prefix . '/store/{idStore}/queue/{idQueue}/turn/{idTurn}', function ($idStore, $idQueue, $idTurn) {
            return (new TurnManager())->delete($idTurn);
        });

        $router->delete($prefix . '/turn/{idTurn}', function ($idTurn) {
            return (new TurnManager())->delete($idTurn);
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

            $configDao = new ConfigDao();
            $minuteInterval = $configDao->findByKey('MIN_DURATION_BUCKETS')->getValue();
            $configDao->close();

            if (date('i') % $minuteInterval == 0) {
                return (new TurnManager())->updateHourTurns();
            }

            return array('done' => false);
        });

        $router->get($prefix . '/test', function () {
            return 'Aloha';
        });

        // -----------------------------------------------------------------
        // ---------------------------- CONFIG -----------------------------
        // -----------------------------------------------------------------

        $router->get($prefix . '/config', function () {
            return (new ConfigManager())->findAll();
        });

        $router->post($prefix . '/config', function () {
            $body = file_get_contents('php://input');
            $body = json_decode($body);
            return (new ConfigManager())->updateConfigs($body);
        });

    }
}
