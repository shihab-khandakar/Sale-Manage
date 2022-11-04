<?php

namespace App\Providers\RepositoriesProvider;

use Illuminate\Support\ServiceProvider;

class CommonServiceProvider extends ServiceProvider
{

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Repositories\Common\CommonInterface',
            'App\Repositories\Common\CommonRepository'
        );
    }
}
