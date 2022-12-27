<?php
namespace App\Services;

use Str;
use Cookie;
use Carbon\Carbon;

use DeviceDetect;

use \App\Models\Account;

use function \App\Helpers\getUserAgent;
use function \App\Helpers\createUniqueModel;

class Accounts {

    protected $cookieLifeTime;

    protected $cookieName = 'account';

    protected $currentAccountHash = false;

    public function __construct() {
        // 5 years
        $this->cookieLifeTime = 60*24*365*5;

        $this->currentAccountHash = Cookie::get($this->cookieName);
    }

    /**
     * Get current account
     */
    public function get($field=null) {
        $r = null;

        if ($this->currentAccountHash) {
            $r = $this->updateUserAgent(
                Account::byHash($this->currentAccountHash)->first()
            );
        }

        return $field ? data_get($r, $field) : $r;
    }

    /**
     * @param $data Base data ar kādiem izveidot model
     */
    public function getOrCreate($data = []) {
        if ($account = $this->get()) {
            return $account;
        }
        else {
            return $this->updateUserAgent($this->create($data));
        }
    }

    public function create($data) {
        // Izveidojam jaunu account un uzliekam cookie
        return $this->setCookie(createUniqueModel(Account::class, 'hash', $data));
    }

    public function setCookie($account) {
        Cookie::queue($this->cookieName, $account->hash, $this->cookieLifeTime);

        return $account;
    }

    public function unsetCookie() {
        Cookie::queue($this->cookieName, '', -1);
    }

    public function updateUserAgent($account) {
        if ($account) {
            $account->user_agent = getUserAgent();

            // Update device info if user_agent have changed
            if ($account->isDirty('user_agent')) {
                $account->device_info = DeviceDetect::getInfo($account->user_agent);
                $account->save();
            }
        }

        return $account;
    }

    /**
     * Atgriež to tablet accounts, kuri gaida uz approve skaitu
     */
    public function getPendingTabletsCount() {
        return Account::byTablet()->byUnApproved()->count();
    }
}
