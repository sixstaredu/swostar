<?php
namespace SwoStar\Consul;

use SwoStar\Supper\ServerProvider;

/**
 *
 */
class ConsulServerProvider extends ServerProvider
{

    public function register()
    {

    }

    public function boot()
    {
        // 读取配置
        $config = $this->app->make("config");

        // 加载consul服务对象
        $this->app->bind("consul", new Agent(new Consul($config->get('consul.consul.host'),$config->get('consul.consul.port'))));
    }
}
