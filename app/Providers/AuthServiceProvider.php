<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot() : void
    {
        $this->registerPolicies();
        Gate::define('modules', function($user, $permissionName) {
            if($user->publish == 0) 
                return false;
            // Lấy ra danh sách permisssion
            $permission = $user->user_catalogues->permissions;
            // Kiểm tra permissionName có nằm trong permission trả ra hay không
            if($permission->contains('canonical', $permissionName)){
                return true;
            }
            return false;
        });
    }
}
