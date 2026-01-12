<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Services\Permissions\ProjectPermissionResolver;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\Notification::class => \App\Policies\InvitationPolicy::class,
//        \App\Models\Tasklist::class => \App\Policies\TasklistPolicy::class,
//        \App\Models\Task::class => \App\Policies\TaskPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::before(function (User $user,string $ability, array $params = []) {
            if (empty($params)) {
                return null;
            }

            $project_id = (int) last($params);

            $resolver = app(ProjectPermissionResolver::class);

            return $resolver->check($user, $ability, $project_id);
        });
    }
}
