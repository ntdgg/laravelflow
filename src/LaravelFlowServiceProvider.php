<?php

namespace LaravelFlow;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class LaravelFlowServiceProvider extends BaseServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        #
    }

    public function boot()
    {
        // 加载路由
        $this->loadRoutesFrom(__DIR__ . '/routes/api.php');
        // 加载配置
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'laravelflow');

        // 发布静态文件文件
        $this->publishes([__DIR__ . '/../resource/assets/work' => public_path('static')], "laravelflow-assets");

        // 发布配置文件
        $this->publishes([__DIR__ . '/../config/LaravelFlow.php' => config_path('LaravelFlow.php')], "laravelflow-config");
    }
}
