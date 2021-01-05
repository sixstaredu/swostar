<?php
namespace SwoStar\Consul;

use Swoole\Coroutine\Http\Client;

class Consul
{
    protected $host;

    protected $port;

    function __construct($host, $port)
    {
        $this->host = $host;
        $this->port = $port;
    }

    public function get(string $url = null, $options = [])
    {
        return $this->request('GET', $url, $options);
    }

    public function put(string $url = null, $options = [])
    {
        return $this->request('PUT', $url, $options);
    }

    private function request($method, $uri, $options)
    {
        // dd($uri, "cons");
        $client = new Client($this->host, $this->port);
        $client->setMethod($method);
        if (!empty($options)) {
            // dd($options, "参数");
            $client->setData(\json_encode($options['body']));
        }
        $client->execute($uri);

        // Response
        $headers    = $client->headers;
        $statusCode = $client->statusCode;
        $body       = $client->body;

        $client->close();
        return Response::new($headers, $body, $statusCode);
    }
}
