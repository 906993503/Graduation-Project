<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:web,admin')->except('logout');
    }
    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        $credentials = $this->credentials($request);
        $credentials['active'] = 1;
        $user = User::where('email', $credentials['email'])->first();
        if ($user['is_admin']) {
            return Auth::guard('admin')->attempt(
                $credentials,
                $request->filled('remember')
            );
        } else {
            return Auth::guard()->attempt(
                $credentials,
                $request->filled('remember')
            );
        }
        exit();
    }
    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        $credentials = $this->credentials($request);
        $user = User::where('email', $credentials['email'])->first();
        if ($user['is_admin']) {
            $user = Auth::guard('admin')->user();
        } else {
            $user = Auth::user();
        }
        return response()->json(['status' => true, 'user' => $user]);
    }
}
