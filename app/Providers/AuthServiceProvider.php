<?php

namespace App\Providers;
use App\Ticsol\Base\Policies;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Ticsol\Components\Models\Job' => 'App\Ticsol\Base\Policies\JobPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::Routes(function($router){
            $router->forAccessTokens();
        });
        //
    }
}
