<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if ($request->is('web')) {
            return route('login');
        }

            //     dd('u');
            //     return route('admin.login');
            // } 
            // dd(! Auth::guard('web')->check());

            // if ($request->is('admin') || $request->is('admin/*')) {
            //     dd('u');
            //     return route('admin.login');
            // } 

            // if (! $request->expectsJson()) {
            //     return route('login');
            // }
        // }
    }
}
