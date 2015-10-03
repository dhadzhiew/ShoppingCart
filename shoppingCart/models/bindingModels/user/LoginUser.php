<?php

namespace DH\ShoppingCart\Models\BindingModels\User;

class LoginUser
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