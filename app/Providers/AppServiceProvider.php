<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;


class AppServiceProvider extends ServiceProvider
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
        // ✅ Church Admin
        Blade::if('churchAdmin', function () {
            $user = Auth::user();

            return $user && $user->isAdmin(session('church_id'));
        });

        // ✅ Church User
        Blade::if('churchUser', function () {
            $user = Auth::user();

            return $user && $user->isUser(session('church_id'));
        });
    }
}
