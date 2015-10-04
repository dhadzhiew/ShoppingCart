<?php

namespace DH\Mvc;

include_once 'Loader.php';

class App
{
    private static $instance;
    private $config = null;
    private $frontController;
    private $router = null;
    private $dbConnections;
    private $session;

    private function __construct()
    {
        set_exception_handler(array($this, '_exceptionHandler'));
        \DH\Mvc\Loader::registerNamespace('DH\Mvc', dirname(__FILE__) . DIRECTORY_SEPARATOR);
        \DH\Mvc\Loader::registerAutoLoad();
        $this->config = \DH\Mvc\Config::getInstance();
    }

    public function run()
    {
        if ($this->config->getConfigFolder() == null) {
            $this->config->setConfigFolder("../config");
        }

        $this->frontController = \DH\Mvc\FrontController::getInstance();
        if ($this->router instanceof \DH\Mvc\Routers\IRouter) {
            $this->frontController->setRouter($this->router);
        } elseif ($this->router == 'JsonRPCRouter') {
            // TODO fix when RPC is done
            $this->frontController->setRouter(new \DH\Mvc\Routers\DefaultRouter());
        } elseif ($this->router == 'CLIRouter') {
            $this->frontController->setRouter(new \DH\Mvc\Routers\DefaultRouter());
        } else {
            $this->frontController->setRouter(new \DH\Mvc\Routers\DefaultRouter());
        }

        $_sessionConfig = $this->config->app['session'];
        if ($_sessionConfig['autostart']) {
            if ($_sessionConfig['type'] == 'native') {
                $_s = new \DH\Mvc\Session\NativeSession(
                    $_sessionConfig['name'],
                    $_sessionConfig['lifetime'],
                    $_sessionConfig['path'],
                    $_sessionConfig['domain'],
                    $_sessionConfig['secure']);
            } elseif ($_sessionConfig['type'] == 'database') {
                $_s = new \DH\Mvc\Session\DBSession(
                    $_sessionConfig['dbConnection'],
                    $_sessionConfig['name'],
                    $_sessionConfig['dbTable'],
                    $_sessionConfig['lifetime'],
                    $_sessionConfig['path'],
                    $_sessionConfig['domain'],
                    $_sessionConfig['secure']
                );
            } else {
                throw new \Exception('No valid session.', 500);
            }

            $this->setSession($_s);
        }

        $this->frontController->dispatch();
    }

    /**
     * @return \DH\Mvc\App
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new \DH\Mvc\App();
        }

        return self::$instance;
    }

    public function setConfigFolder($path)
    {
        $this->config->setConfigFolder($path);
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
        return $this->config;
    }

    public function getRouter()
    {
        return $this->router;
    }

    public function setRouter($router)
    {
        $this->router = $router;
    }

    public function setSession(\DH\Mvc\Session\ISession $session)
    {
        $this->session = $session;
    }

    public function getSession()
    {
        return $this->session;
    }

    public function getDBConnection($connection = 'default')
    {
        if (!$connection) {
            throw new \Exception('No connection identifier provided.', 500);
        }
        if ($this->dbConnections[$connection]) {
            return $this->dbConnections[$connection];
        }

        $databaseConfig = $this->getConfig()->database;
        if (!$databaseConfig[$connection]) {
            throw new \Exception('No valid connection identifier provided.', 500);
        }

        $dbh = new \PDO(
            $databaseConfig[$connection]['dsn'],
            $databaseConfig[$connection]['username'],
            $databaseConfig[$connection]['password'],
            $databaseConfig[$connection]['pdo_options']);
        $this->dbConnections[$connection] = $dbh;

        return $dbh;
    }

    public function displayError($errorCode)
    {
        try {
            $view = \DH\Mvc\View::getInstance();
            View::title('Error ' . $errorCode);
            $view->appendToLayout('header', 'header');
            $view->appendToLayout('body', 'errors.' . $errorCode);
            $view->appendToLayout('footer', 'footer');
            $view->display('layouts.default');
        } catch(\Exception $ex) {
            $error = \DH\Mvc\Common::headerStatus($errorCode);
            echo '<h1>' . $error . '</h1>';
            exit;
        }
    }

    public function _exceptionHandler(\Exception $ex)
    {
        if ($this->config && $this->config->app['displayExceptions'] === true) {
            echo '<pre>' . print_r($ex, true) . '</pre>';
        } else {
            $this->displayError($ex->getCode());
        }
    }

    public function __destruct()
    {
        if ($this->session != null) {
            $this->session->saveSession();
        }
    }

}