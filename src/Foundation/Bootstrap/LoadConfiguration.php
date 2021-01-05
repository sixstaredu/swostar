<?php
namespace SwoStar\Foundation\Bootstrap;

use SwoStar\Config\Config;

use SwoStar\Foundation\Application;

class LoadConfiguration
{
    public function bootstrap(Application $app)
    {
        $app->bind("config", new Config($app->getConfigPaht()));
    }
}
