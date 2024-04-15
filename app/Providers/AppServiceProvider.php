<?php

namespace App\Providers;

use App\Models\Coupon;
use App\Models\Product;
use Laravel\Passport\Passport;
use App\Observers\CouponObserver;

use App\Observers\ProductObserver;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use Laravel\Dusk\DuskServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];
    public function register()
    {
        if ($this->app->environment('local', 'testing')) {
            $this->app->register(DuskServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Schema::disableForeignKeyConstraints();
       
        Coupon::observe(CouponObserver::class);
        Product::observe(ProductObserver::class);
        Passport::routes();
        //
    }
}
