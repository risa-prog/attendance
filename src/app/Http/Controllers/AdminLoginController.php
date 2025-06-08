<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;


class AdminLoginController extends Controller
{
    public function showLoginForm () {
        return view('auth.login_admin');
    }
    
    public function login (LoginRequest $request) {
        $credentials = $request->only('email', 'password');
        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
    
            return redirect('/admin/attendance/list');
        }

        return back()->withErrors(['email' => 'ログイン情報が登録されていません']);
    }
}
