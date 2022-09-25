<?php

namespace App\Providers;

use App\Support\CustomMaintenanceMode;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Foundation\MaintenanceModeManager;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->extend(
            MaintenanceModeManager::class,
            function (MaintenanceModeManager $manager) {
                $manager->extend('custom', function (Container $container) {
                    return new CustomMaintenanceMode(
                        $container->make(FilesystemManager::class),
                        $container->make(Repository::class)->get('app.maintenance.disk'),
                    );
                });

                return $manager;
            }
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
