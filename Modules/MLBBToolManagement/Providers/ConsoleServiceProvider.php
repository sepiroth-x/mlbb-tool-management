<?php

namespace Modules\MLBBToolManagement\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\MLBBToolManagement\Console\GenerateHeroImages;

class ConsoleServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->commands([
            GenerateHeroImages::class,
        ]);
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [];
    }
}
