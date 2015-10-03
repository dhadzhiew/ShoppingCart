<?php

namespace DH\ShoppingCart\Models\BindingModels\User;


use DH\Mvc\BaseBindingModel;

class RegisterUser extends  BaseBindingModel
{
    /**
     * [minLength(2)]
     */
    public $username;
    /**
     * [email]
     */
    public $email;
    /**
     * [minLength(4)]
     * [matches({passwordAgain})]
     */
    public $password;
    public $passwordAgain;
}