<?php
/**
 * Created by PhpStorm.
 * User: yous
 * Date: 11/06/18
 * Time: 19:01
 */

require dirname(__FILE__) . '/../controller/StoreManager.php';

class StoreRouterManager {

    public static function manageRoutes(Phroute\Phroute\RouteCollector $router) {

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
    }

}