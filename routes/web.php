<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;


Route::get('login', [LoginController::class, 'showLoginForm'])->name('admin-login');
Route::post('login', [LoginController::class, 'login']);
Route::get('logout', [LoginController::class, 'logout'])->name('logout');





Route::group([
    'middleware' => 'auth.admin:admin',
], function(){
    Route::get('/admin', function(){

        dump(\Auth::user());

        return 'admin';
    });
});

Route::group([
    'middleware' => 'auth.tablet:tablet'
], function(){
    Route::get('/tablet', function(){

        return 'tablet approved';
    });
});

Route::get('/tablet/login', function(){
    return 'tablet login or request access';
})->name('tablet-login');



Route::group([
    'middleware' => 'auth:web2',
], function(){
    Route::get('/web2', function(){
        return 'web2';
    });
});




Route::get('/aa', function(){
    return 'aa';
});