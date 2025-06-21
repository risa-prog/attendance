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

    public function emailVerificationCheck() {
        $user = Auth::user();
        if($user->email_verified_at === null) {
            return back()->with(
                'message',
                '送付したメールから認証を完了させてください');
        } else {
            return redirect('/attendance');
        }
    }

    public function emailVerify(EmailVerificationRequest $request) {
        $request->fulfill(); 
        //  email_verified_atを更新
        return view('auth.email_verification_complete');
    }

    public function resend(Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', '認証リンクを再送しました。');
    }

    public function emailVerificationRedirect() {
        return view('auth.email_verification');
    }
}
