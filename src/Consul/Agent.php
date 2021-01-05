<?php
namespace SwoStar\Consul;

class Agent
{
    /**
     * [protected description]
     * @var Consul
     */
    protected $consul;

    function __construct(Consul $consul)
    {
        $this->consul = $consul;
    }

    public function services()
    {
        return $this->consul->get("/v1/agent/services");
    }

    public function registerService($service)
    {
        $params = [
            'body' => $service
        ];

        return $this->consul->put('/v1/agent/service/register', $params);
    }

    public function deregisterService(string $serviceId): Response
    {
        return $this->consul->put('/v1/agent/service/deregister/' . $serviceId);
    }

    public function health($serverName)
    {
        return $this->consul->get("/v1/health/service/".$serverName."?passing=true");
    }
}
