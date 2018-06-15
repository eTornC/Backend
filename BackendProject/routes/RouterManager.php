<?php

require dirname(__FILE__) . '/../controller/StoreManager.php';
require dirname(__FILE__) . '/../controller/QueueManager.php';

class RouterManager {

    public static function manageRoutes(Phroute\Phroute\RouteCollector $router) {

        // -----------------------------------------------------------------
        // ---------------------------- STORES -----------------------------
        // -----------------------------------------------------------------

        $router->get('/stores', function () {
            return (new StoreManager())->findAll();
        });

        $router->get('/store/{id}', function ($id) {
            return (new StoreManager())->findById($id);
        });

        $router->post('/store', function () {
            $body = file_get_contents('php://input');
            return (new StoreManager())->save(json_decode($body));
        });

        $router->put('/store/{id}', function ($id) {
            $body = file_get_contents('php://input');
            return (new StoreManager())->update(json_decode($body), $id);
        });

        $router->delete('/store/{id}', function ($id) {
            return (new StoreManager())->delete($id);
        });

        // -----------------------------------------------------------------
        // ---------------------------- QUEUES -----------------------------
        // -----------------------------------------------------------------

        $router->get('/store/{idStore}/queues', function ($idStore) {
            return (new QueueManager())->findAll($idStore);
        });

        $router->get('/store/{idStore}/queue/{idQueue}', function ($idStore, $idQueue) {
            return (new QueueManager())->findById($idStore, $idQueue);
        });

        $router->post('/store/{idStore}/queue', function($idStore) {
            $body = file_get_contents('php://input');
            return (new QueueManager())->save(json_decode($body), $idStore);
        });

        $router->put('/store/{idStore}/queue/{idQueue}', function ($idStore, $idQueue) {
            $body = file_get_contents('php://input');
            return (new QueueManager())->update(json_decode($body), $idStore, $idQueue);
        });

        $router->delete('/store/{idStore}/queue/{idQueue}', function ($idStore, $idQueue) {
            return (new QueueManager())->delete($idStore, $idQueue);
        });

        // -----------------------------------------------------------------
        // ---------------------------- BUCKETS ----------------------------
        // -----------------------------------------------------------------

        $router->get('/store/{idStore}/queue/{idQueue}/buckets', function ($idStore, $idQueue) {
            return '';
        });

        $router->get('/store/{idStore}/queue/{idQueue}/bucket/{idBucket}', function ($idStore, $idQueue, $idBucket) {
            return '';
        });

        $router->post('/store/{idStore}/queue/{idQueue}/bucket', function ($idStore, $idQueue) {
            $body = file_get_contents('php://input');
            return '';
        });

        $router->put('/store/{idStore}/queue/{idQueue}/bucket/{idBucket}', function ($idStore, $idQueue, $idBucket) {
            $body = file_get_contents('php://input');
            return '';
        });

        $router->delete('/store/{idStore}/queue/{idQueue}/bucket/{idBucket}', function ($idStore, $idQueue, $idBucket) {
            return '';
        });

        // -----------------------------------------------------------------
        // ---------------------------- TURNS ------------------------------
        // -----------------------------------------------------------------
    }

}

?>