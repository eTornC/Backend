<?php

namespace eTorn\Routes;

use eTorn\Controller\LayoutManager;
use eTorn\Controller\PublicityManager;
use Phroute\Phroute\RouteCollector;

class LayoutsRoutes
{
    public static function manageRoutes(RouteCollector $router)
    {
        $prefix = '';

        // -----------------------------------------------------------------
        // ---------------------------- LAYOUTS ----------------------------
        // -----------------------------------------------------------------

        $router->get($prefix . '/layouts', function () {
            return (new LayoutManager())->findAll();
        });

        $router->get($prefix . "/layout/{id}", function ($id) {
            return (new LayoutManager())->findById($id);
        });

        $router->get($prefix . '/turns-screens', function () {
            return (new LayoutManager())->findAllTurnsScreen();
        });

        $router->get($prefix . '/totem-screens', function () {
            return (new LayoutManager())->findAllTotemScreen();
        });

        $router->post($prefix . '/turns-screen', function () {
            $body = file_get_contents('php://input');
            return (new LayoutManager())->save(\json_decode($body), 'TURNSCREEN');
        });

        $router->post($prefix . '/totem-screen', function () {
            $body = file_get_contents('php://input');
            return (new LayoutManager())->save(\json_decode($body), 'TOTEMSCREEN');
        });

        $router->post($prefix . '/layout-template', function () {
            $body = file_get_contents('php://input');
            return (new LayoutManager())->save(\json_decode($body), 'TEMPLATE');
        });
        $router->put($prefix . '/layout/{id}', function ($id) {
            $body = file_get_contents('php://input');
            return (new LayoutManager())->update(\json_decode($body),$id);
        });
        $router->delete($prefix . "/layout/{id}", function ($id) {
            return (new LayoutManager())->delete($id);
        });


        // -----------------------------------------------------------------
        // --------------------------- PUBLICITY ---------------------------
        // -----------------------------------------------------------------
        $router->get($prefix . '/publicities', function () {
            return (new PublicityManager())->findAll();
        });

        $router->get($prefix . "/publicity/{id}", function ($id) {
            return (new PublicityManager())->findById($id);
        });

        $router->post($prefix . '/publicity', function () {
            $body = file_get_contents('php://input');
            return (new PublicityManager())->save(json_decode($body));
        });

        $router->put($prefix . '/publicity/{id}', function ($id) {
            $body = file_get_contents('php://input');
            return (new PublicityManager())->update(json_decode($body), $id);
        });
        $router->delete($prefix . '/publicity/{id}', function ($id) {
            return (new PublicityManager())->delete($id);
        });
    }
}