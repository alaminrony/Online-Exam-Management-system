<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\AclUserGroupToAccess;
use DB;
use Common;
use Auth;
use Helper;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        view()->composer('*', function ($view) {

            //get request notification number on topnavber in all views
            if (Auth::check()) {

               
            }
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
