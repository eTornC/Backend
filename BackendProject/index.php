<?php

    // ----------------------------------------------------------------------------------
    // ------------------------------- IMPORTS ------------------------------------------
    // ----------------------------------------------------------------------------------
    use Illuminate\Database\Capsule\Manager as Capsule;
    use eTorn\Constants\ConstantsDB;
    use Phroute\Phroute\RouteCollector;
    use Phroute\Phroute\Exception\HttpRouteNotFoundException;
    use Phroute\Phroute\Exception\HttpMethodNotAllowedException;
    use eTorn\Routes\RouterManager;

    require(dirname(__FILE__) . '/vendor/autoload.php');
    require(dirname(__FILE__) . '/routes/RouterManager.php');

    // ----------------------------------------------------------------------------------
    // ------------------------------- ERRORS CONFIG ------------------------------------
    // ----------------------------------------------------------------------------------
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // ----------------------------------------------------------------------------------
    // ------------------------------- HEADERS CONFIG -----------------------------------
    // ----------------------------------------------------------------------------------
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Credentials: false");
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');

    // ----------------------------------------------------------------------------------
    // ------------------------------- ELOQUENT CONFIG ----------------------------------
    // ----------------------------------------------------------------------------------

    $capsule = new Capsule;

    $capsule->addConnection([
        "driver"    => "mysql",
        "host"      => ConstantsDB::DB_SERVER,
        "database"  => ConstantsDB::DB_NAME,
        "username"  => ConstantsDB::DB_USER,
        "password"  => ConstantsDB::DB_PASSWD
    ]);

    //Make this Capsule instance available globally.
    $capsule->setAsGlobal();

    // Setup the Eloquent ORM.
    $capsule->bootEloquent();

    // ----------------------------------------------------------------------------------
    // ------------------------------- ROUTER CONFIG ------------------------------------
    // ----------------------------------------------------------------------------------

    $router = new RouteCollector();

    RouterManager::manageRoutes($router);

    $dispatcher = new Phroute\Phroute\Dispatcher($router->getData());

    $response = '';

    try {
        $response = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    } catch (HttpRouteNotFoundException $e) {
        $response = array("Error" => "Route not found");
    } catch (HttpMethodNotAllowedException $e) {
        $response = array("Error" => "Route not found or incorrect method.");
    }

    // ----------------------------------------------------------------------------------
    // ------------------------ CLOSING CONNECTION TO DATABASE --------------------------
    // ----------------------------------------------------------------------------------
    $capsule->getDatabaseManager()->disconnect(ConstantsDB::DB_NAME);

    // ----------------------------------------------------------------------------------
    // ------------------------------------ RESPONSE ------------------------------------
    // ----------------------------------------------------------------------------------

    if (is_array($response) || is_object($response)) {
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($response);
    } else {
        echo $response;
    }
