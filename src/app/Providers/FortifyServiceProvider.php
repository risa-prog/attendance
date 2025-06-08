<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
         Fortify::createUsersUsing(CreateNewUser::class);

        Fortify::ignoreRoutes();

        
    }
}      









        // Fortify::loginView(function () {
        //     if (request()->is('admin/*')) {
        //     return view('auth.login_admin');
        //     }
        //     return view('auth.login');
        // });


        
        // RateLimiter::for('login',function(Request $request) {
        //     $email = (string) $request->email;

        //     return Limit::perMinute(10)->by($email . $request->ip());
        // });

        
    //      Fortify::authenticateUsing(function (Request $request) {
    //          if ($request->is('admin/*')) {
    //              $admin = Admin::where('email', $request->email)->first();

    //              if ($admin && Hash::check($request->password, $admin->password)) {
    //              Auth::guard('admin')->login($admin);
    //              return $admin;
    //              }
    //          } else {
    //              $user = User::where('email', $request->email)->first();
    //              if ($user && Hash::check($request->password, $user->password)) {
    //              Auth::login($user);
    //              return $user;
    //              }
    //          }
    //      });
    // }

