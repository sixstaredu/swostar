<?php
namespace SwoStar\Routes;

use SwoStar\Supper\ServerProvider;
/**
 *
 */
class RouteServerProvider extends ServerProvider
{
    protected $map;

    public function boot()
    {
        $this->app->bind('route', Route::getInstance($this->map)->registerRoute());

        dd($this->app->make('route')->getRoutes(), "获取所有注册的route");
    }
}
