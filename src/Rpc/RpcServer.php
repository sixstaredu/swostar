<?php
namespace SwoStar\Rpc;

use SwoStar\Foundation\Application;
use SwoStar\Message\Response;

use SwoStar\Server\ServerBase;
use Swoole\Server;

class RpcServer
{
    /**
     * @var Application
     */
    protected $app;
    /**
     * @var \SwoStar\Config\Config
     */
    protected $config;
    /**
     * @var ServerBase
     */
    protected $server;
    /**
     * @var Swoole\Server
     */
    protected $listen;

    function __construct(Application $app, ServerBase $server)
    {
        $this->app = $app;
        $this->server = $server;
        $this->config = $app->make('config');
        $this->listen = $server->getServer()->listen($this->config->get('server.rpc.host'), $this->config->get('server.rpc.port'), $this->config->get('server.rpc.type'));

        $this->listen->on('connect', [$this, 'connect']);
        $this->listen->on('receive', [$this, 'receive']);
        $this->listen->on('close'  , [$this, 'close']);
        $this->listen->set($this->config->get('server.rpc.swoole'));


        dd("开启rpc服务：tcp://".$this->config->get('server.rpc.host').":".$this->config->get('server.rpc.port'));
    }

    public function connect($serv, $fd)
    {
    }

    public function receive(Server $serv, $fd, $from_id, $data)
    {
        /*json: {
          "method" : "class::action",
          "params" : []
        }*/

        $oper = json_decode($data, true);

        $class = \explode("::", $oper['method'])[0];
        $class = new $class();
        $ret = $class->{\explode("::", $oper['method'])[1]}(...$oper["params"]);

        $serv->send($fd, Response::send($ret));
    }

    public function close($serv, $fd)
    {
        echo "Client: Close.\n";
    }
}
