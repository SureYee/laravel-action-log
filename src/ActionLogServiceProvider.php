<?php

namespace Sureyee\ActionLog;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Sureyee\ActionLog\Observers\ActionLogObserver;

class ActionLogServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/rock_fin_tech.php' => config_path('rock_fin_tech.php')
        ]);

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->bootObservers();
    }


    protected function bootObservers()
    {
        $watchingModels = Config::get('actionlog.watching');

        foreach ($watchingModels as $model) {
            $model::observe(ActionLogObserver::class);
        }
    }
}