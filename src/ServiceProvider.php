<?php

namespace Rockbuzz\SpClient;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider as SupportServiceProvider;

class ServiceProvider extends SupportServiceProvider
{

    public function boot(Filesystem $filesystem)
    {
        $this->publishes([
            __DIR__ . '/config/sp_client.php' => config_path('sp_client.php')
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/sp_client.php', 'sp_client');
    }
}
