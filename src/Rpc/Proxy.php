<?php
namespace SwoStar\Rpc;

/**
 *
 */
class Proxy
{
    /**
     * @var Application
     */
    protected $app;

    protected $services;

    function __construct($app, $services)
    {
        $this->app = $app;
        $this->services = $services;
    }

    protected function services($servername = '')
    {
        if (is_array($this->services)) {
            return $this->services;
        }
        if ($this->services instanceof \Closure ){
            return ($this->services)($servername);
        }
        if (empty($this->services)) {
            return $this->app->make("config")->get("service.".$servername);
        }
    }

    public function getService($servername = '')
    {
        $providers = $this->services($servername);
        $randKey  = array_rand($providers, 1);
        return $providers[$randKey];
    }
}
