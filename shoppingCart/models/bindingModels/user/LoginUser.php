<?php

namespace DH\ShoppingCart\Models\BindingModels\User;

use DH\Mvc\BaseBindingModel;

class LoginUser extends BaseBindingModel
{
    /**
     * [required]
     * [minLength(2)]
     */
    public $username;
    /**
     * [required]
     */
    public $password;
}