<?php
namespace Controllers;

use DH\Mvc\BaseController;
use DH\Mvc\Validation;
use DH\Mvc\View;
use DH\ShoppingCart\Models\UserModel;
use DH\ShoppingCart\Models\ViewModels\User\RegisterUserViewModel;

class UsersController extends BaseController
{
    public function register()
    {
        $userViewModel = new RegisterUserViewModel();
        if($this->input->post('submit')) {
            $username = $this->input->post('username', 'trim');
            $email = $this->input->post('email', 'trim');
            $pass = $this->input->post('pass', 'trim');
            $passAgain = $this->input->post('passAgain', 'trim');

            if(Validation::matches($pass, $passAgain)) {
                $userModel = new UserModel();
                $result = $userModel->register($username, $email, $pass);
                $userViewModel->errors = array_merge($result, $userViewModel->errors);
            } else {
                $userViewModel->errors[] = 'Passwords do not matches.';
            }
            
            if(!count($userViewModel->errors)) {
                $userViewModel->success = true;
            }
        }
        $view = View::getInstance();
        $view->setTitle('Register');
        $view->appendToLayout('header', 'header');
        $view->appendToLayout('body', 'user.register');
        $view->appendToLayout('footer', 'footer');
        $view->display('layouts.default', $userViewModel);
    }

    public function login()
    {
//        $userModel = new \DH\ShoppingCart\Models\UserModel();


        $view = View::getInstance();
        $view->setTitle('Login');
        $view->appendToLayout('header', 'header');
        $view->appendToLayout('body', 'user.login');
        $view->appendToLayout('footer', 'footer');
        $view->display('layouts.default');
    }
}