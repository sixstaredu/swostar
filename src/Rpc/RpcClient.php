<?php
namespace SwoStar\Rpc;

use Swoole\Coroutine\Client;

class RpcClient
{

    protected $service = "";

    protected $classType;

    /**
     * 代理请求转发
     * @method proxy
     * 六星教育 @shineyork老师
     * @return [type] [description]
     */
    protected function proxy($method, $arguments)
    {
        /*json: {
          "method" : "class::action",
          "params" : []
        }*/
        $data = [
            'method' => $this->classType."::".$method,
            'params' => $arguments
        ];

        // 获取到服务配置信息
        // $config = app("config")->get("service.".$this->service);
        $provider = app("rpc_client")->getService($this->service);

        return $this->send($provider['host'], $provider['port'], $data);
    }

    protected function send($host, $port, $data)
    {
        // 利用协程请求发送
        $cli = new Client(SWOOLE_SOCK_TCP);

        if (!$cli->connect($host, $port)) {
            // dd("连接失败");
            throw new \Exception("无法找到rpc服务 ：".$host." : ".$port, 500);
        }
        $cli->send(\json_encode($data));

        $ret = $cli->recv();

        $cli->close();

        return $ret;
    }

    public function __call($method, $arguments)
    {
        return $this->proxy($method, $arguments);
    }
}
