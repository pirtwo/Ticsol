<?php

namespace App\Providers;

use App\Ticsol\Base\Policies;
use App\Ticsol\Components\Models;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate; 

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Models\Client::class => Policies\ClientPolicy::class,
        Models\User::class => Policies\UserPolicy::class,
        Models\Role::class => Policies\RolePolicy::class,
        Models\Job::class => Policies\JobPolicy::class,
        Models\Form::class => Policies\FormPolicy::class,
        Models\Team::class => Policies\TeamPolicy::class,
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

        // ====== GATES =======
        Gate::define('edit-QBs', function ($user) {
            return $user->isowner;
        });


        // ====== PASSPORT =======
        Passport::tokensCan([
            'view' => 'view the resource',
            'create' => 'create new resource',
            'update' => 'update the resource',
            'delete' => 'delete the resource',
        ]);

        // Passport routes
        Passport::Routes(function ($router) {
            $router->forAccessTokens();
            $router->forAuthorization();
        });

        Passport::enableImplicitGrant();
    }
}
