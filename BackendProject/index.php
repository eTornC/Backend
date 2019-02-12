<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Credentials: false");
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');

    require(dirname(__FILE__) . '/vendor/autoload.php');
    require(dirname(__FILE__) . '/routes/RouterManager.php');

    use Phroute\Phroute\RouteCollector;
    use eTorn\Routes\RouterManager;

    $router = new RouteCollector();

    RouterManager::manageRoutes($router);

    $dispatcher = new Phroute\Phroute\Dispatcher($router->getData());

    $response = '';

    try {
        $response = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    } catch (\Phroute\Phroute\Exception\HttpRouteNotFoundException $e) {
        $response = array("Error" => "Route not found");
    } catch (\Phroute\Phroute\Exception\HttpMethodNotAllowedException $e) {
        $response = array("Error" => "Route not found or incorrect method.");
    }

    if (is_array($response)) {
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($response);
    } else {
        echo $response;
    }
?>