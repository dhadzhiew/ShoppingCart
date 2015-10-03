<?php
$config["DH\ShoppingCart\Controllers"]["users"]["register"]["DH\ShoppingCart\Models\BindingModels\User\RegisterUser"] = ["username" => [["minLength", "2"]], "email" => ["email"], "password" => [["minLength", "4"], ["matches", "{passwordAgain}"]], "passwordAgain" => [], "modelState" => [], "errors" => []];
$config["DH\ShoppingCart\Controllers"]["users"]["login"]["DH\ShoppingCart\Models\BindingModels\User\LoginUser"] = ["username" => ["required", ["minLength", "2"]], "password" => ["required"], "modelState" => [], "errors" => []];

return $config;