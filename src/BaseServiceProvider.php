<?php

namespace Winata\PackageBased;

use Illuminate\Support\ServiceProvider;

/**
 * Class BaseServiceProvider
 *
 * Base service provider for the Winata PackageBased package.
 * This can be extended by other service providers within the package
 * to register and boot package-specific services.
 *
 * @package Winata\PackageBased
 */
class BaseServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * This method is used to bind services into the container.
     *
     * @return void
     */
    public function register(): void
    {
        // You may bind classes or interfaces here.
    }

    /**
     * Bootstrap any application services.
     *
     * This method is used to perform any actions required
     * after all services have been registered.
     *
     * @return void
     */
    public function boot(): void
    {
        // You may publish configs, routes, or load views here.
    }
}
