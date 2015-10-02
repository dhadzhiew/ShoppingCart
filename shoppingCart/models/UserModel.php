<?php

namespace DH\ShoppingCart\Models;

use DH\Mvc\Common;
use DH\Mvc\DB\SimpleDB;
use DH\Mvc\Validation;

class UserModel extends SimpleDB
{
    public function __construct()
    {
        parent::__construct();
    }

    public function register($username, $email, $password, $passwordAgain)
    {
        $errors = [];
        $username = Common::normalize($username, 'trim');
        $email = Common::normalize($email, 'trim');
        $password = Common::normalize($password, 'trim');
        $validation = new Validation();
        $validation
            ->setRule('minLength', $username, 2, 'Short username')
            ->setRule('email', $email, null, 'Invalid email')
            ->setRule('minLength', $password, 4, 'Short password')
            ->setRule('matches', $password, $passwordAgain, 'Passwords do not matches.');

        $validation->validate();
        $errors = $validation->getErrors();

        if(!count($errors)) {
            $checkUser = $this->prepare('SELECT username, email FROM users WHERE username = ? OR email = ?')
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
                $this->prepare('INSERT INTO users(username, email, password) VALUES (?, ?, ?)')
                    ->execute(
                        array(
                            $username,
                            $email,
                            password_hash($password, PASSWORD_DEFAULT)
                        )
                    );

                if($this->getSTMT()->errorInfo()[0] != 0) {
                    $errors[] = 'Database error.';
                }
            }
        }

            return $errors;
    }

    public function login($username, $password)
    {
        $user = $this->prepare('SELECT id,password FROM users WHERE username = ?')
                    ->execute(array($username))
                    ->fetchRowAssoc();
        if($user && password_verify($password, $user['password'])) {
            return array(
                'id' => $user['id']
            );
        }

        return false;
    }

    public function getUserInfo($id)
    {
        return $this->prepare('SELECT username, email FROM users WHERE id = ?')
            ->execute(array($id))
            ->fetchRowAssoc();
    }
}