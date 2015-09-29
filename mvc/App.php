<?php

namespace DH\Mvc;

include_once 'Loader.php';

class App
{
    private static $_instance;
    private $_config = null;
    private $_frontController;
    private $router = null;

    private function __construct()
    {
        \DH\Mvc\Loader::registerNamespace('DH\Mvc', dirname(__FILE__) . DIRECTORY_SEPARATOR);
        \DH\Mvc\Loader::registerAutoLoad();
        $this->_config = \DH\Mvc\Config::getInstance();
    }

    public function run()
    {
        if ($this->_config->getConfigFolder() == null) {
            $this->_config->setConfigFolder("../config");
        }

        $this->_frontController = \DH\Mvc\FrontController::getInstance();
        if($this->router instanceof \DH\Mvc\Routers\IRouter) {
            $this->_frontController->setRouter($this->router);
        }
        elseif ($this->router == 'JsonRPCRouter') {
            // TODO fix when RPC is done
            $this->_frontController->setRouter(new \DH\Mvc\Routers\DefaultRouter());
        } elseif ($this->router == 'CLIRouter') {
            $this->_frontController->setRouter(new \DH\Mvc\Routers\DefaultRouter());
        } else {
            $this->_frontController->setRouter(new \DH\Mvc\Routers\DefaultRouter());
        }

        $this->_frontController->dispatch();
    }

    /**
     * @return \DH\Mvc\App
     */
    public static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new \DH\Mvc\App();
        }

        return self::$_instance;
    }

    public function setConfigFolder($path)
    {
        $this->config . $this->setConfigFolder($path);
    }

    public function getConfigFolder()
    {
        return $this->config->getConfigFolder();
    }

    /**
     * @return Config|null
     */
    public function getConfig()
    {
        return $this->_config;
    }

    public function getRouter()
    {
        return $this->router;
    }

    public function setRouter($router)
    {
        $this->router = $router;
    }


}