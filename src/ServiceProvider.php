<?php

namespace Rockbuzz\SpClient;

use DomainException;
use Illuminate\Support\Collection;
use Illuminate\Http\Client\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\ServiceProvider as SupportServiceProvider;
use ReflectionClass;
use Rockbuzz\SpClient\Data\Base;

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

        Collection::macro('mapDataWithType', function (string $calssName) {
            if (!(new ReflectionClass($calssName))->isSubclassOf(Base::class)) {
                throw new DomainException(
                    "Class '{$calssName}' must be a Rockbuzz\SpClient\Data\Base::class subclass."
                );
            }
            /** @var Collection $this */
            return $this->map(function ($items, $key) use ($calssName) {
                if ('data' === $key) {
                    return $calssName::arrayOf($items);
                }
                return $items;
            });
        });
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/sp_client.php', 'sp_client');
    }
}
