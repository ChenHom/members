<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPermissions();
    }

    protected function registerPermissions(): void
    {
        try {
            Permission::pluck('name')->each(
                fn ($permission) => Gate::define(
                    $permission,
                    fn (User $user) => $user->hasPermission($permission)
                )
            );
        } catch (\Throwable $th) {
            info('權限註冊: 在資料庫中沒有找到權限資料，應用程式將會略過使用者權限審查功能。');
        }
    }
}
