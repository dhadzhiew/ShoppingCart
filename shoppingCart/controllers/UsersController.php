<?php

namespace DH\ShoppingCart\Controllers;

use DH\Mvc\BaseController;
use DH\Mvc\View;
use DH\ShoppingCart\Models\UserModel;
use DH\ShoppingCart\Models\ViewModels\User\ProfileUser;
use DH\ShoppingCart\Models\ViewModels\User\RegisterUser;

class UsersController extends BaseController
{
    /**
     * [Route("register")]
     */
    public function register()
    {
        if($this->session->userId != null) {
            $this->redirect('/profile');
        }

        $userViewModel = new RegisterUser();
        if ($this->input->post('submit')) {
            $username = $this->input->post('username', 'trim');
            $email = $this->input->post('email', 'trim');
            $pass = $this->input->post('pass', 'trim');
            $passAgain = $this->input->post('passAgain', 'trim');

            $userModel = new UserModel();
            $userViewModel->errors = $userModel->register($username, $email, $pass, $passAgain);

            if (!count($userViewModel->errors)) {
                $userViewModel->success = true;
            }
        }

        $view = View::getInstance();
        View::title('Register');
        $view->appendToLayout('header', 'header');
        $view->appendToLayout('body', 'user.register');
        $view->appendToLayout('footer', 'footer');
        $view->display('layouts.default', $userViewModel);
    }
    /**
     * [Route("login")]
     */
    public function login(\DH\ShoppingCart\Models\BindingModels\User\LoginUser $model = null)
    {
        if($this->session->userId != null) {
            $this->redirect('/profile');
        }

        $viewModel = new \DH\ShoppingCart\Models\ViewModels\User\LoginUser();
        if ($model) {

            if($model->modelState) {
                $username = $model->username;
                $password = $model->password;

                $userModel = new UserModel();
                $result = $userModel->login($username, $password);

                if (!$result) {
                    $viewModel->errors[] = 'Invalid password.';
                } else {
                    $this->session->userId = $result['id'];
                    $this->redirect('/profile');
                }
            } else {
                $viewModel->errors = $model->errors;
            }

        }

        $view = View::getInstance();
        View::title('Login');
        $view->appendToLayout('header', 'header');
        $view->appendToLayout('body', 'user.login');
        $view->appendToLayout('footer', 'footer');
        $view->display('layouts.default', $viewModel);
    }

    /**
     * [Route("profile")]
     */
    public function profile()
    {
        if($this->session->userId == null) {
            $this->redirect('/login');
        }

        $userModel = new UserModel();
        $userInfo = $userModel->getUserInfo($this->session->userId);
        $viewModel = new ProfileUser();
        $viewModel->username = $userInfo['username'];

        $view = View::getInstance();
        View::title('Profile');
        $view->appendToLayout('header', 'header');
        $view->appendToLayout('body', 'user.profile');
        $view->appendToLayout('footer', 'footer');
        $view->display('layouts.default', $viewModel);
    }
    /**
     * [Route("logout")]
     */
    public function logout()
    {
        $this->session->destroySession();
        $this->redirect('/login');
    }
}