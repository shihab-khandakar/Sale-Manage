<?php

namespace App\Providers\RepositoriesProvider;

use Illuminate\Support\ServiceProvider;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Repositories\RolePermission\PermissionInterface',
            'App\Repositories\RolePermission\PermissionRepository'
        );
    }
}
