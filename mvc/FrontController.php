<?php

namespace DH\Mvc;

use DH\Mvc\Routers\IRouter;

class FrontController
{
    private static $_instance = null;
    private $namespace = null;
    private $controller = null;
    private $method = null;
    private $router = null;

    /**
     * @return \DH\Mvc\Routers\IRouter
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @param \DH\Mvc\Routers\IRouter $router
     */
    public function setRouter(\DH\MVC\Routers\IRouter $router)
    {
        $this->router = $router;
    }

    private function __construct()
    {

    }

    public function dispatch()
    {
        if($this->router == null) {
            throw new \Exception('No valid router found.', 500);
        }
        $_uri = $this->router->getURI();
        $routes = \DH\Mvc\App::getInstance()->getConfig()->routes;
        $_routeController = null;
        if (is_array($routes) && count($routes) > 0) {
            foreach ($routes as $key => $value) {
                if (stripos($_uri, $key) === 0 && ($_uri == $key || stripos($_uri, $key . '/') === 0) && $value['namespace']) {
                    $this->namespace = $value['namespace'];
                    $_uri = substr($_uri, strlen($key) + 1);
                    $_routeController = $value;
                    break;
                }
            }
        } else {
            throw new \Exception('Default route missing.', 500);
        }

        if ($this->namespace == null && $routes['*']['namespace']) {
            $this->namespace = $routes['*']['namespace'];
            $_routeController = $routes['*'];
        } elseif ($this->namespace == null && !$routes['*']['namespace']) {
            throw new \Exception('Default route missing', 500);
        }

        $_params = explode('/', $_uri);

        if ($_params[0]) {
            $this->controller = strtolower(array_shift($_params));

            if ($_params[0]) {
                $this->method = strtolower(array_shift($_params));
            } else {
                $this->method = $this->getDefaultMethod();
            }
        } else {
            $this->controller = $this->getDefaultController();
            $this->method = $this->getDefaultMethod();
        }

        if (is_array($_routeController) && $_routeController['controllers']) {
            if ($_routeController['controllers'][$this->controller]['methods'][$this->method]) {
                $this->method = strtolower($_routeController['controllers'][$this->controller]['methods'][$this->method]);
            }

            if ($_routeController['controllers'][$this->controller]['to']) {
                $this->controller = strtolower($_routeController['controllers'][$this->controller]['to']);
            }
        }
//        echo $this->controller . '<br/>';
//        echo $this->method . '<br/>';

        $namespaceClass = $this->namespace . '\\' . ucfirst($this->controller);
        $newController = new $namespaceClass();
        $newController->{$this->method}();
    }

    public function getDefaultController()
    {
        $app = \DH\Mvc\App::getInstance();
        $controller = $app->getConfig()->app['default_controller'];
        if ($controller) {
            return strtolower($controller);
        }

        return 'index';
    }

    public function getDefaultMethod()
    {
        $app = \DH\Mvc\App::getInstance();
        $method = $app->getConfig()->app['default_method'];
        if ($method) {
            return strtolower($method);
        }

        return 'index';
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