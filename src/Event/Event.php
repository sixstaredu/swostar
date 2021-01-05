<?php
namespace SwoStar\Event;

/**
 *
 */
class Event
{
    protected $events = [];

    /**
     * 事件注册
     * 六星教育 @shineyork老师
     * @param  string $event    事件标识
     * @param  Closure $callback 事件回调函数
     */
    public function register($event, $callback)
    {
        $event = \strtolower($event);

        // 判断事件是否存在
        // if (condition) {
        //   // code...
        // }

        $this->events[$event] = ['callback' => $callback];
    }
    /**
     * 事件的触发函数
     * 六星教育 @shineyork老师
     * @param  string $event 事件标识
     * @param  array  $param 事件参数
     */
    public function trigger($event, $param = [])
    {
        $event = \strtolower($event);

        if (isset($this->events[$event])) {
            ($this->events[$event]['callback'])(...$param);
            return true;
        }
    }

    public function getEvents($event = null)
    {
        return empty($event) ? $this->events : $this->events[$event];
    }
}
