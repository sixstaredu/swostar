<?php
namespace SwoStar\Foundation\Bootstrap;

use SwoStar\Foundation\Application;

class ServerProviders
{
    public function bootstrap(Application $app)
    {
        $prioviders = $app->make("config")->get('app.prioviders');

        foreach ($prioviders as $key => $priovider) {
            $priovider = new $priovider($app);
            $priovider->register();
            $priovider->boot();
        }
    }
}
