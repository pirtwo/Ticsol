<?php

namespace App\Providers;

use App\Ticsol\Base\Policies;
use App\Ticsol\Components\Models;
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
        Models\Job::class => Policies\JobPolicy::class,
        Models\Role::class => Policies\RolePolicy::class,
        Models\User::class => Policies\UserPolicy::class,
        Models\Form::class => Policies\FormPolicy::class,
        Models\Request::class => Policies\RequestPolicy::class,
        Models\Comment::class => Policies\CommentPolicy::class,
        Models\Contact::class => Policies\ContactPolicy::class,
        Models\Activity::class => Policies\ActivityPolicy::class,
        Models\Schedule::class => Policies\SchedulePolicy::class,
        Models\Timesheet::class => Policies\TimesheetPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();       

        // Passport routes
        Passport::Routes(function ($router) {
            $router->forAccessTokens();
        });
    }
}
