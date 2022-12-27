<?php namespace App\Auth;

use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;

class TextUserProvider implements UserProvider {

    private $users = [
        'kasparsb@hotmail.com' => [
            'email' => 'kasparsb@hotmail.com',
            'password' => 'asdf',
            'caps' => [
                'dashboard',
                'channels',
            ],
            'homeRoute' => '/',
        ],
    ];

    public function retrieveById($identifier) {
        return array_key_exists($identifier, $this->users) ? new User($this->users[$identifier]) : null;
    }

    public function retrieveByCredentials(array $credentials) {
        return array_key_exists($credentials['email'], $this->users) ? new User($this->users[$credentials['email']]) : null;
    }

    public function retrieveByToken($identifier, $token) {

    }

    public function updateRememberToken(Authenticatable $user, $token) {

    }

    public function validateCredentials(Authenticatable $user, array $credentials) {
        return $user->getAuthPassword() == $credentials['password'];
    }
}