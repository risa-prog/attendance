<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Symfony\Component\HttpFoundation\Request;


class EmailVerificationController extends Controller
{
    public function index () {
        return view('auth.email_verification');
    }


    public function emailVerify(EmailVerificationRequest $request) {
        $request->fulfill(); 
        
        return redirect('/attendance');
    }

    public function resend(Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', '認証リンクを再送しました。');
    }

    public function emailVerificationRedirect() {
        return view('auth.email_verification');
    }
}
