<?php

namespace Rockbuzz\SpClient;

use Illuminate\Http\Client\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\ServiceProvider as SupportServiceProvider;

class ServiceProvider extends SupportServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/sp_client.php' => config_path('sp_client.php')
        ], 'config');

        Response::macro('resolveForClient', function () {
            /** @var Response $this */
            if ($this->successful()) {
                return $this;
            }

            if ($this->status() === 422) {
                throw ValidationException::withMessages($this['errors']);
            }

            return $this->throw();
        });
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/sp_client.php', 'sp_client');
    }
}
