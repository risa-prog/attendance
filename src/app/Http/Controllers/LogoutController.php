<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class LogoutController extends Controller
{
    public function logout(Request $request) {
        Auth::guard('web')->logout(); // webガードでログアウト

        $request->session()->invalidate(); // セッション破棄
        $request->session()->regenerateToken(); // CSRFトークン再生成

        return view('auth.login');

    }
}
