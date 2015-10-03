<?php

namespace DH\ShoppingCart\Models\BindingModels\User;

class LoginUser
{
    /**
     * [required]
     */
    public $username;
    /**
     * [minLength(2)]
     */
    public $password;
}