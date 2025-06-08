<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CorrectionListRedirector
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('admin')->check()) {
            if ($request->tab === null) {
                return redirect()->route('admin.collection.list');
            } else {
                return redirect()->route('admin.collection.list',['tab' => $request->tab]);
            }
        } elseif (Auth::guard('web')->check()) {
            if ($request->tab === null) {
                return redirect()->route('user.collection.list');
            } else {
                return redirect()->route('user.collection.list', ['tab' => $request->tab]);
            }
        }

        return redirect()->route('login');
    
    }
}
