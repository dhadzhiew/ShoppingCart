<?php
namespace Controllers;

use DH\Mvc\BaseController;
use DH\Mvc\Validation;
use DH\Mvc\View;
use DH\ShoppingCart\Models\UserModel;
use DH\ShoppingCart\Models\ViewModels\User\LoginUserViewModel;
use DH\ShoppingCart\Models\ViewModels\User\ProfileUserViewModel;
use DH\ShoppingCart\Models\ViewModels\User\RegisterUserViewModel;

/**
 * [RoutePrefix("useri/")]
 */
class UsersController extends BaseController
{
    /**
     * [Route("kon")]
     */
    public function register()
    {
        if($this->session->userId != null) {
            $this->redirect('/users/profile');
        }

        $userViewModel = new RegisterUserViewModel();
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
    public function login()
    {
        if($this->session->userId != null) {
            $this->redirect('/users/profile');
        }

        $viewModel = new LoginUserViewModel();
        if ($this->input->post('submit')) {
            $username = $this->input->post('username', 'trim');
            $password = $this->input->post('pass', 'trim');

            $userModel = new UserModel();
            $result = $userModel->login($username, $password);

            if (!$result) {
                $viewModel->errors[] = 'Invalid password.';
            } else {
                $this->session->userId = $result['id'];
                $this->redirect('/users/profile');
            }

        }

        $view = View::getInstance();
        View::title('Login');
        $view->appendToLayout('header', 'header');
        $view->appendToLayout('body', 'user.login');
        $view->appendToLayout('footer', 'footer');
        $view->display('layouts.default', $viewModel);
    }

    public function profile()
    {
        if($this->session->userId == null) {
            $this->redirect('/users/login');
        }

        $userModel = new UserModel();
        $userInfo = $userModel->getUserInfo($this->session->userId);
        $viewModel = new ProfileUserViewModel();
        $viewModel->username = $userInfo['username'];

        $view = View::getInstance();
        View::title('Profile');
        $view->appendToLayout('header', 'header');
        $view->appendToLayout('body', 'user.profile');
        $view->appendToLayout('footer', 'footer');
        $view->display('layouts.default', $viewModel);
    }

    public function logout()
    {
        $this->session->destroySession();
        $this->redirect('/users/login');
    }
}