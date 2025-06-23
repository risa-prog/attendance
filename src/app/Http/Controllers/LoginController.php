<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm () {
        return view('auth.login');
    }

    public function login(LoginRequest $request){
       $credentials = $request->only('email', 'password');
        if (Auth::guard('web')->attempt($credentials)) {
            return redirect('/attendance');
        }
        return back()->withErrors(['email' => 'ログイン情報が登録されていません']);
    }
}
