<?php
$config["DH\ShoppingCart\Controllers"]["users"]["register"]["DH\ShoppingCart\Models\BindingModels\User\RegisterUser"] = ["username" => [["minLength", "2"]], "password" => [["minLength", "4"], ["matches", "{passwordAgain}"]], "email" => ["email"], "passwordAgain" => []];
$config["DH\ShoppingCart\Controllers"]["users"]["login"]["DH\ShoppingCart\Models\BindingModels\User\LoginUser"] = ["username" => ["required", ["minLength", "2"]], "password" => ["required"]];

return $config;