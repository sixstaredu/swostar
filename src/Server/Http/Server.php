<?php
namespace SwoStar\Server\Http;

use SwoStar\Console\Input;
use SwoStar\Message\Response;

use SwoStar\Server\ServerBase;
use Swoole\Http\Server as SwooleServer;
use Swoole\Http\Request as SwooleRequest;
use Swoole\Http\Response as SwooleResponse;
use SwoStar\Message\Http\Request as HttpRequest;

class Server extends ServerBase
{
    public function createServer()
    {
        $this->swooleServer = new SwooleServer($this->host, $this->port);

        Input::info('http server 访问 : http://'.swoole_get_local_ip()['ens33'].':'.$this->port );
    }
    // 初始化默认设置
    protected function initServer()
    {
        $this->port = $this->config->get('server.http.port');
        $this->host = $this->config->get('server.http.host');
        // $this->swooleConfig = $this->config->get('server.http.swoole');
    }

    protected function initEvent()
    {
        $this->setEvent('sub', [
            'request' => 'onRequest',
        ]);
    }

    // onRequest

    public function onRequest(SwooleRequest $request, SwooleResponse $response)
    {
        $uri = $request->server['request_uri'];
        if ($uri == '/favicon.ico') {
            $response->status(404);
            $response->end('');
            return null;
        }

        $httpRequest = HttpRequest::init($request);

        // 执行控制器的方法
        $return = $this->app->make('route')->setFlag('http')->setMethod($httpRequest->getMethod())->match($httpRequest->getUriPath());

        $response->end(Response::send($return));
    }
}
