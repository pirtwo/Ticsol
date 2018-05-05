<?php

namespace App\Ticsol\Components\Providers;

use Illuminate\Support\ServiceProvider;
use App\Ticsol\Components\Models;

class CaptchaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(َICaptcha::class, Captcha::class);
    }
}
