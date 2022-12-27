<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;

class LoginController extends Controller
{

    /**
     * Kuru no config/auth.php guards izmantot
     * jo šeit nevar zināt kāds Guard ir novedis
     * līdz šim login logam
     */
    private $guard = 'web2';

    public function __construct()
    {
        //$this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        //$this->validateLogin($request);

        if ($this->attemptLogin($request)) {
            $request->session()->regenerate();

            return redirect(data_get(Auth::guard($this->guard)->user(), 'homeRoute'));
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        //$this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    public function logout(Request $request)
    {
        Auth::guard($this->guard)->logout();

        //$request->session()->invalidate();

        return redirect('/');
    }

    protected function attemptLogin(Request $req)
    {
        $r = Auth::guard($this->guard)->attempt(
            $req->only('email', 'password'), true
        );

        return $r;
    }
}
