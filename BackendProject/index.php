<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    header('Content-type: application/json; charset=utf-8');

    require(dirname(__FILE__) . '/vendor/autoload.php');
    require(dirname(__FILE__) . '/routes/RouterManager.php');

    use Phroute\Phroute\RouteCollector;

    $router = new RouteCollector();

    RouterManager::manageRoutes($router);

    $dispatcher = new Phroute\Phroute\Dispatcher($router->getData());

    $response = '';

    try {
        $response = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    } catch (\Phroute\Phroute\Exception\HttpRouteNotFoundException $e) {
        $response = array("Error" => "Route not found");
    }

    echo json_encode($response);

?>