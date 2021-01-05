<?php
namespace SwoStar\Server;

use Redis;
use Swoole\Coroutine\Http\Client;
use Swoole\Server as SwooleServer;
use SwoStar\Foundation\Application;
use SwoStar\Config\Config;

/**
 * 所有服务的父类， 写一写公共的操作
 */
abstract class ServerBase
{
    // 属性
    /**
     *
     * @var Swoole/Server
     */
    protected $swooleServer;

    protected $app ;

    protected $port = 9000;

    protected $host = "0.0.0.0";

    /**
     * [protected description]
     * @var SwoStar/Config/Config
     */
    protected $config;
    /**
     * 这是swoole服务的配置
     * @var [type]
     */
    protected $swooleConfig = [
        'task_worker_num' => 0,
    ];
    /**
     * 注册的回调事件
     * [
     *   // 这是所有服务均会注册的时间
     *   "server" => [],
     *   // 子类的服务
     *   "sub" => [],
     *   // 额外扩展的回调函数
     *   "ext" => []
     * ]
     *
     * @var array
     */
    protected $event = [
        // 这是所有服务均会注册的时间
        "server" => [
            // 事件   =》 事件函数
            "start"        => "onStart",
            "workerStart"  => "onWorkerStart",
            // "managerStart" => "onManagerStart",
            // "managerStop"  => "onManagerStop",
            "shutdown"     => "onShutdow",
            // "workerStop"   => "onWorkerStop",
            // "workerError"  => "onWorkerError",
        ],
        // 子类的服务
        "sub" => [],
        // 额外扩展的回调函数
        // 如 ontart等
        "ext" => []
    ];

    public function __construct(Application $app, $flag = 'http')
    {
        $this->flag = $flag;
        $this->app = $app;
        $this->config = $app->make('config');
        // 初始化swoole配置
        $this->initServer();
        // 创建服务
        $this->createServer();
        // 设置回调函数
        $this->initEvent();
        // 设置swoole的回调事件
        $this->setSwooleEvent();
    }
    /**
     * 初始化设置
     */
    protected abstract function initServer();
    /**
    * 初始化监听的事件
    * 六星教育 @shineyork老师
    */
    protected abstract function initEvent();
    /**
     * 创建服务
     * 六星教育 @shineyork老师
     */
    protected abstract function createServer();

    public function start()
    {
        // 2. 设置配置信息
        $this->swooleServer->set($this->swooleConfig);

        // if ($this->config->get('server.http.tcpable')) {
        //     // new Rpc($this->swooleServer, $this->config->get('server.http.rpc'));
        // }
        // 5. 启动
        $this->swooleServer->start();
    }
    /**
     * 设置swoole的回调事件
     * 六星教育 @shineyork老师
     */
    protected function setSwooleEvent()
    {
        foreach ($this->event as $type => $events) {
            foreach ($events as $event => $func) {
                $this->swooleServer->on($event, [$this, $func]);
            }
        }
    }
    // 回调方法
    public function onStart(SwooleServer $server) {
        $this->app->make('event')->trigger('swoole:start', [$this, $server]);
    }

    public function onWorkerStart(SwooleServer $server, int $worker_id) {}

    public function onShutdow($server)
    {
        $this->app->make('event')->trigger('swoole:stop', [$this, $server]);
    }

    // GET | SET

    /**
     * @param array
     *
     * @return static
     */
    public function setEvent($type, $event)
    {
        // 暂时不支持直接设置系统的回调事件
        if ($type == "server") {
            return $this;
        }
        $this->event[$type] = $event;
        return $this;
    }
    /**
     * @return array
     */
    public function getSwooleConfig(): array
    {
        return $this->swooleConfig;
    }
    /**
     * @param array $config
     *
     * @return static
     */
    public function setSwooleConfig($config)
    {
        $this->swooleConfig = array_map($this->swooleConfig, $config);
        return $this;
    }
    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }
    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }
    /**
     * [getServer description]
     * @method getServer
     * 六星教育 @shineyork老师
     * @return Swoole\Server
     */
    public function getServer()
    {
        return $this->swooleServer;
    }
}
