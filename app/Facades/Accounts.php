<?php namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Accounts extends Facade {

    protected static function getFacadeAccessor() {
        return 'App\Services\Accounts';
    }

}