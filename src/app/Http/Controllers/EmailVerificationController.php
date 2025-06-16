<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;


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

    public function complete() {
        return view('email_verification_complete');
    }
}
