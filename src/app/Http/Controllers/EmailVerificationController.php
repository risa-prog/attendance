<?php

namespace App\Http\Controllers;


class EmailVerificationController extends Controller
{
    public function index () {
        return view('auth.email_verification');
    }
}
