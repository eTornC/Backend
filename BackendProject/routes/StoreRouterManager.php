<?php
/**
 * Created by PhpStorm.
 * User: yous
 * Date: 11/06/18
 * Time: 19:01
 */

class StoreRouterManager {

    public static function manageRoutes(Phroute\Phroute\RouteCollector $router) {

        $router->get('/stores', function () {
            return (new StoreManager())->findAll();
        });

        $router->get('/store/{id}', function ($id) {
            return 'this will return the store with id:' . $id;
        });

        $router->post('/store', function () {
            $body = file_get_contents('php://input');
            return 'this will create a store';
        });

        $router->put('/store', function () {
            $body = file_get_contents('php://input');
            return 'this will update a store';
        });

        $router->delete('/store/{id}', function ($id) {
            return 'this will remove the store with id ' . $id;
        });
    }

}