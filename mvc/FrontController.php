<?php

namespace DH\Mvc;

class FrontController
{
    private static $_instance = null;
    private $namespace = null;
    private $controller = null;
    private $method = null;

    private function __construct()
    {

    }

    public function dispatch()
    {
        $router = new \DH\Mvc\Routers\DefaultRouter();
        $_uri = $router->getURI();
        $routes = \DH\Mvc\App::getInstance()->getConfig()->routes;
        $_routeController = null;
        if(is_array($routes) && count($routes) > 0) {
            foreach($routes as $key => $value) {
                if(stripos($_uri, $key) === 0 && ($_uri == $key || stripos($_uri, $key . '/') === 0) && $value['namespace']) {
                    $this->namespace = $value['namespace'];
                    $_uri = substr($_uri, strlen($key) + 1);
                    $_routeController = $value;
                    break;
                }
            }
        }else {
            throw new \Exception('Default route missing.', 500);
        }

        if($this->namespace == null && $routes['*']['namespace']) {
            $this->namespace = $routes['*']['namespace'];
            $_routeController = $routes['*'];
        } elseif($this->namespace == null &&  !$routes['*']['namespace']) {
            throw new \Exception('Default route missing', 500);
        }

        $_params = explode('/', $_uri);

        if($_params[0]) {
            $this->controller = array_shift($_params);

            if($_params[0]) {
                $this->method = array_shift($_params);
            }else {
                $this->method = $this->getDefaultMethod();
            }
        } else {
            $this->controller = $this->getDefaultController();
            $this->method = $this->getDefaultMethod();
        }

        if(is_array($_routeController) && $_routeController['controllers']
            && $_routeController['controllers'][$this->controller]) {
            $this->controller = $_routeController['controllers'][$this->controller];
        }
        echo $this->controller . '<br/>';
        echo $this->method . '<br/>';
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