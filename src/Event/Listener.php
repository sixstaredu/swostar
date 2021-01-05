<?php
namespace SwoStar\Event;

use SwoStar\Foundation\Application;

abstract class Listener
{
    protected $name = 'listener';

    protected $app ;

    public abstract function handler();

    public function __construct(Application $app )
    {
        $this->app = $app;
    }

    public function getName()
    {
        return $this->name;
    }
}
