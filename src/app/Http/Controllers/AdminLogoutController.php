<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLogoutController extends Controller
{
    public function logout(Request $request) {
        Auth::guard('admin')->logout();

        $request->session()->invalidate(); // セッション破棄
        $request->session()->regenerateToken(); // CSRFトークン再生成

        return view('auth.login_admin');

    }

}