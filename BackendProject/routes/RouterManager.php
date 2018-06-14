<?php
/**
 * Created by PhpStorm.
 * User: yous
 * Date: 11/06/18
 * Time: 19:01
 */

require dirname(__FILE__) . '/../controller/StoreManager.php';
require dirname(__FILE__) . '/../controller/QueueManager.php';

class RouterManager {

    public static function manageRoutes(Phroute\Phroute\RouteCollector $router) {

        // ---------------------------- STORES ----------------------------

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

        // ---------------------------- QUEUES ----------------------------

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
    }

}

?>