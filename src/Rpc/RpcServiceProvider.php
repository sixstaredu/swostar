<?php
namespace SwoStar\Rpc;

use SwoStar\Supper\ServerProvider;

/**
 *
 */
class RpcServiceProvider extends ServerProvider
{

    protected $services;

    public function boot()
    {
        $this->provider();

        $this->app->bind('rpc_client', new Proxy($this->app, $this->services));
    }

    protected function provider()
    {

    }
}
