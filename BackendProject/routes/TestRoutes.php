<?php

namespace eTorn\Routes;

use eTorn\Constants\ConstantsDB;
use eTorn\Controller\Logger;
use Phroute\Phroute\RouteCollector;

class TestRoutes
{
    public static function manageRoutes(RouteCollector $router)
    {
        $prefix = '';

        // -----------------------------------------------------------------
        // ---------------------------- DEPLOY -----------------------------
        // -----------------------------------------------------------------

        $router->any($prefix . '/deploy', function () {

            $user = ConstantsDB::DB_USER;
            $password = ConstantsDB::DB_PASSWD;
            $host = ConstantsDB::DB_SERVER;

            $script_path = \getcwd() . '/scripts/etorn.sql';

            $command = "mysql --user={$user} --password='{$password}' "
                . "-h {$host} < {$script_path}";

            $output = shell_exec($command);

            return 'done ' . $output;
        });

        // -----------------------------------------------------------------
        // ---------------------------- TESTINGS  --------------------------
        // -----------------------------------------------------------------

        $router->get($prefix . '/test', function ()
        {
            $now = time();
            $hour_start = (ceil($now / 300) * 300) - 300;
            return date('Y-m-d H:i:s', $hour_start);
        });

        $router->get($prefix . '/time', function ()
        {
            Logger::debug('aloha');
            return time() . '   ' . (time() + 300);
        });

        $router->get($prefix . '/phpinfo', function ()
        {
            return phpinfo();
        });
    }
}