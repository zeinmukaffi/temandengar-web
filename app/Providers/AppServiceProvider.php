<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

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
        View::composer('layouts.dashboard', function ($view) {

            $user = Auth::user();

            $view->with(
                'unreadNotificationCount',
                $user
                ? $user->unreadNotifications()->count()
                : 0
            );

            $view->with(
                'recentNotifications',
                $user
                ? $user->notifications()->latest()->limit(5)->get()
                : collect()
            );

        });
    }
}
