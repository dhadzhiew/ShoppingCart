<?php
/**
 * Created by PhpStorm.
 * User: Hadzhiew
 * Date: 18.9.2015 ã.
 * Time: 13:05
 */

namespace DH\Mvc;

class FrontController
{
    private static $_instance = null;

    private function __construct()
    {

    }

    public function dispatch()
    {
        $router = new \DH\Mvc\Routers\DefaultRouter();
        echo $router->getURI();
    }

    public function getDefaultController()
    {
        $app = \DH\Mvc\App::getInstance();
        $controller = isset($app->getConfig()->app['default_controller']) ? $app->getConfig()->app['default_controller'] : null;
        if ($controller) {
            return $controller;
        }

        return 'Index';
    }

    public function getDefaultMethod()
    {
        $app = \DH\Mvc\App::getInstance();
        $method = isset($app->getConfig()->app['default_method']) ? $app->getConfig()->app['default_method'] : null;
        if ($method) {
            return $method;
        }

        return 'Index';
    }

    /**
     * @return \DH\Mvc\FrontController
     */
    public static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new  \DH\Mvc\FrontController();
        }

        return self::$_instance;
    }
}