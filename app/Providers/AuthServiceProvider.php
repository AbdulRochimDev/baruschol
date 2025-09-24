<?php
namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    public function boot()
    {
        $this->registerPolicies();
        Gate::define('isRole', function (User $user, string $role) {
            // Strict check: only consult roles pivot table. Tests/seeders must attach roles explicitly.
            try {
                return $user->roles()->where('slug', $role)->exists();
            } catch (\Throwable $e) {
                // If relation/migration not present, deny by default to avoid accidental privilege escalation
                return false;
            }
        });
    }
}
