<?php

namespace Controllers;

class HomeController extends \DH\Mvc\BaseController
{
    public function Index()
    {

        $viewModel =  new \DH\ShoppingCart\Models\ViewModels\HomeViewModel();
        $viewModel->setBody('Body') ;

        $view = \DH\Mvc\View::getInstance();
        $view->appendToLayout('header', 'header');
        $view->appendToLayout('body', 'index');
        $view->appendToLayout('footer', 'footer');
        $view->display('layouts.default', $viewModel);

    }
}