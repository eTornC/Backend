<?php

require dirname(__FILE__) . '/../controller/StoreManager.php';
require dirname(__FILE__) . '/../controller/QueueManager.php';

class RouterManager {

    public static function manageRoutes(Phroute\Phroute\RouteCollector $router) {

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
            $body = file_get_contents('php://input');
            return (new StoreManager())->save(json_decode($body));
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
        // ---------------------------- BUCKETS ----------------------------
        // -----------------------------------------------------------------

        $router->get($prefix . '/store/{idStore}/queue/{idQueue}/buckets', function ($idStore, $idQueue) {
            return '';
        });

        $router->get($prefix . '/store/{idStore}/queue/{idQueue}/bucket/{idBucket}', function ($idStore, $idQueue, $idBucket) {
            return '';
        });

        $router->post($prefix . '/store/{idStore}/queue/{idQueue}/bucket', function ($idStore, $idQueue) {
            $body = file_get_contents('php://input');
            return '';
        });

        $router->put($prefix . '/store/{idStore}/queue/{idQueue}/bucket/{idBucket}', function ($idStore, $idQueue, $idBucket) {
            $body = file_get_contents('php://input');
            return '';
        });

        $router->delete($prefix . '/store/{idStore}/queue/{idQueue}/bucket/{idBucket}', function ($idStore, $idQueue, $idBucket) {
            return '';
        });

        // -----------------------------------------------------------------
        // ---------------------------- TURNS ------------------------------
        // -----------------------------------------------------------------

        $router->get($prefix . '/store/{idStore}/queue/{idQueue}/turns', function ($idStore, $idQueue) {
            return '';
        });

    }

}

?>