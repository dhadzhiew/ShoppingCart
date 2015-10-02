<?php

namespace DH\ShoppingCart\Models;

use DH\Mvc\Common;
use DH\Mvc\DB\SimpleDB;
use DH\Mvc\Validation;

class UserModel
{
    public function register($username, $email, $password)
    {
        $errors = [];
        $username = Common::normalize($username, 'trim');
        $email = Common::normalize($email, 'trim');
        $password = Common::normalize($password, 'trim');

        $validation = new Validation();
        $validation
            ->setRule('minLength', $username, 2, 'Short username')
            ->setRule('email', $email, null, 'Invalid email')
            ->setRule('minLength', $password, 4, 'Short password');

        $validation->validate();
        $errors = $validation->getErrors();

        if(!count($errors)) {
            $db = new SimpleDB('default');
            $checkUser = $db->prepare('SELECT username, email FROM users WHERE username = ? OR email = ?')
                                ->execute(array($username, $email))
                                ->fetchRowAssoc();
            if($checkUser) {
                if($checkUser['username'] == $username) {
                    $errors[] = 'Username already exists.';
                }
                if($checkUser['email'] == $email) {
                    $errors[] = 'Email already exists.';
                }
            }

            if(!count($errors)) {
                $db->prepare('INSERT INTO users(username, email, password) VALUES (?, ?, ?)')
                    ->execute(
                        array(
                            $username,
                            $email,
                            password_hash($password, PASSWORD_BCRYPT)
                        )
                    );

                if($db->getSTMT()->errorInfo()[0] != 0) {
                    $errors[] = 'Database error.';
                }
            }
        }

        return $errors;
    }
}