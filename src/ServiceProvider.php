<?php

namespace LaravelFlow;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/api.php');
        $this->mergeConfigFrom(__DIR__ . '/../config/laravelflow.php', 'laravelflow');
    }
    public function boot()
    {
        // 发布静态文件文件
        $this->publishes([__DIR__ . '/../resource/assets/work' => public_path('static')], "laravelflow-assets");

        // 发布配置文件
        $this->publishes([__DIR__ . '/../config/laravelflow.php' => config_path('laravelflow.php')], "laravelflow-config");
    }
}
