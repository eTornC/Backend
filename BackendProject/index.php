<?php
/**
 * Created by PhpStorm.
 * User: yous
 * Date: 11/06/18
 * Time: 11:25
 */


    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    header('Content-type: application/json; charset=utf-8');

    require(dirname(__FILE__) . '/vendor/autoload.php');
    require(dirname(__FILE__) . '/routes/StoreRouterManager.php');

    use Phroute\Phroute\RouteCollector;

    $router = new RouteCollector();

    StoreRouterManager::manageRoutes($router);

    $dispatcher = new Phroute\Phroute\Dispatcher($router->getData());

    $response = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    echo $response;
?>