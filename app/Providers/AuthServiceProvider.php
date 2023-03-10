<?php

namespace App\Providers;

use App\Models\User;
use App\Models\ObatApotek;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.
        
        //UNTUK CRUD OBAT
        Gate::define('read-obat', function ($user){
            return $user->role == 'customer' || $user->role == 'admin';
        });

        Gate::define('update-obat', function ($user)
        {
           if ($user->role == 'admin') {
            return true;
           } else {
            return false;
           }
        });

        Gate::define('create-obat', function ($user)
        {
           if ($user->role == 'admin') {
            return true;
           } else {
            return false;
           }
        });

        Gate::define('destroy-obat', function ($user)
        {
           if ($user->role == 'admin') {
            return true;
           } else {
            return false;
           }
        });

        Gate::define('show-obat', function ($user)
        {
           if ($user->role == 'admin') {
            return true;
           } else {
            return false;
           }
        });

        //UNTUK CRUD SUPPLIER
        Gate::define('read-supplier', function ($user){
            return $user->role == 'customer' || $user->role == 'admin';
        });

        Gate::define('update-supplier', function ($user)
        {
           if ($user->role == 'admin') {
            return true;
           } else {
            return false;
           }
        });

        Gate::define('create-supplier', function ($user)
        {
           if ($user->role == 'admin') {
            return true;
           } else {
            return false;
           }
        });

        Gate::define('destroy-supplier', function ($user)
        {
           if ($user->role == 'admin') {
            return true;
           } else {
            return false;
           }
        });

        Gate::define('show-supplier', function ($user)
        {
           if ($user->role == 'admin') {
            return true;
           } else {
            return false;
           }
        });

        $this->app['auth']->viaRequest('api', function ($request) {
            if ($request->input('api_token')) {
                return User::where('api_token', $request->input('api_token'))->first();
            }
        });
    }
}
