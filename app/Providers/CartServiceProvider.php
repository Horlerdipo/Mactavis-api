<?php

namespace App\Providers;

use App\Services\Cart\CartInterface;
use App\Services\Cart\Providers\LaravelCart;
use Illuminate\Support\ServiceProvider;

class CartServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(CartInterface::class, function($app) {
            return new LaravelCart();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
