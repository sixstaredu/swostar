<?php
namespace SwoStar\Foundation;

use SwoStar\Console\Input;
use SwoStar\Container\Container;
use SwoStar\Rpc\RpcServer;
use SwoStar\Server\Http\Server as  HttpServer;

class Application extends Container
{
    protected const SWOSTAR_WELCOME = "
      _____                     _____     ___
     /  __/             ____   /  __/  __/  /__   ___ __      __  __
     \__ \  | | /| / / / __ \  \__ \  /_   ___/  /  _`  |    / /_/ /
     __/ /  | |/ |/ / / /_/ /  __/ /   /  /_    |  (_|  |   /  ___/
    /___/   |__/\__/  \____/  /___/    \___/     \___/\_|  /__/
    ";

    protected $basePath = "";

    protected $bootstraps = [
        Bootstrap\LoadConfiguration::class,
        Bootstrap\ServerProviders::class
    ];

    public function __construct($path = null)
    {
        if (!empty($path)) {
            $this->setBasePath($path);
        }

        self::setInstance($this);

        $this->bootstrap();

        Input::info(self::SWOSTAR_WELCOME, "启动项目");
    }
    /**
     * 基于驱动加载框架
     * @method registerBaseBindings
     * 六星教育 @shineyork老师
     */
    protected function bootstrap(){
        foreach ($this->bootstraps as $key => $bootstrap) {
            (new $bootstrap())->bootstrap($this);
        }
    }

    public function run($arg)
    {
        $args = explode(":", $arg[1]);

        $server = null;

        switch ($arg[1]) {
          case 'http:start':
            $server = new HttpServer($this);
            break;
          case 'ws:start':
            // $server = new WebSocketServer($this);
            break;
        }
        // 判断是否开启rpc服务
        if ($this->make('config')->get('server.rpc.flag')) {
            new RpcServer($this, $server);
        }

        $server->start();
    }

    public function getConfigPaht()
    {
        return $this->getBasePath().'/config';
    }

    public function setBasePath($path)
    {
        $this->basePath = \rtrim($path, '\/');
    }
    public function getBasePath()
    {
        return $this->basePath;
    }
}
