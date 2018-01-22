<?php

namespace App\Http\Controllers;

use Corp\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Handle an authentication attempt.
     * @param Request $request
     */
    public function authenticate(Request $request)
    {
        dd($request);

//        if (Auth::attempt(['email' => $email, 'password' => $password])) {
//            // Authentication passed...
//            return redirect()->intended('dashboard');
//        }
    }

    public function showLoginForm()
    {
        dd('Test');

        return view(env('THEME') . '.login');
    }
}