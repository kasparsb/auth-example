<?php namespace App\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Guard;

use Accounts;

class AccountGuard implements Guard
{
    private $provider;
    private $user;

    public static function getAccountTablet() {
        $account = Accounts::getOrCreate();

        if ($account->is_approved && ($account->is_tablet || $account->is_admin)) {
            return $account;
        }

        return null;
    }

    public static function getAccountAdmin() {
        $account = Accounts::getOrCreate();

        /**
         * is_approved drīzāk jāpārsauc par is_logged_in
         */

        if ($account->is_approved && $account->is_admin) {
            return $account;
        }

        return null;
    }

    public function __construct(UserProvider $provider)
    {
        $this->provider = $provider;
        $this->user = null;
    }

    public function check()
    {
        return ! is_null($this->user());
    }

    public function guest()
    {
        return ! $this->check();
    }

    public function hasUser() {
        return $this->user ? true : false;
    }

    public function setUser(?Authenticatable $user)
    {
        $this->user = $user;
    }

    public function user()
    {
        if (isset($this->user)) {
            return $this->user;
        }

        $account = Accounts::get();

        // Izvelkam user auth_identifier no $account
        $authIdentifier = data_get($account, 'info.user_auth_identifier');
        $user = $this->provider->retrieveById($authIdentifier);

        // Ja ir user, tad atgriežam account
        return $account;
    }

    public function id()
    {
        if ($this->user) {
            return $this->user->getAuthIdentifier();
        }
    }

    public function validate(array $credentials = [])
    {
        if (!isset($credentials['username']) || empty($credentials['username']) || !isset($credentials['password']) || empty($credentials['password'])) {
            return false;
        }

        $user = $this->provider->retrieveById($credentials['username']);

        if (!$user) {
            return false;
        }

        if ($this->provider->validateCredentials($user, $credentials)) {
            $this->setUser($user);
            return true;
        } else {
            return false;
        }
    }

    public function attempt(array $credentials = [], $remember = false)
    {
        //$this->fireAttemptEvent($credentials, $remember);

        $this->lastAttempted = $user = $this->provider->retrieveByCredentials($credentials);

        if ($this->provider->validateCredentials($user, $credentials)) {
            $this->login($user);

            return true;
        }


        //$this->fireFailedEvent($user, $credentials);

        return false;
    }

    public function login($user) {
        // Šeit piesaistām account ielogotā user epastu
        $account = Accounts::getOrCreate();

        $account->type = 'admin';
        $account->approved_at = now();
        $account->save();

        $account->setInfo('user_auth_identifier', $user->getAuthIdentifier());

        $this->setUser($user);
    }

    public function logout() {
        $account = Accounts::get();

        if ($account) {
            $account->type = null;
            $account->approved_at = null;
            $account->save();

            $account->setInfo('user_auth_identifier', '');
        }

        $this->setUser(null);
    }
}