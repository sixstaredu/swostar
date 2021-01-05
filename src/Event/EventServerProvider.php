<?php
namespace SwoStar\Event;

use SwoStar\Config\Config;

use SwoStar\Supper\ServerProvider;
/**
 *
 */
class EventServerProvider extends ServerProvider
{

    public function boot()
    {
        $event = new Event();

        $config = $this->app->make("config");
        // 根据监听地址注册事件
        $this->regListenenrs($event, $config);
        // 添加自定义事件
        $this->regEvents($event, $config);

        // 注册到系统中
        $this->app->bind('event', $event);
    }

    protected function regListenenrs(Event $event, Config $config)
    {
        $listeners = $config->get("event.Listeners");

        foreach ($listeners as $key => $listener) {
            $files = scandir($this->app->getBasePath().$listener["path"]);

            // 2. 读取文件信息
            foreach ($files as $key => $file) {
                if ($file === '.' || $file === '..') {
                    continue;
                }

                $class = $listener["namespace"].\explode('.', $file)[0];

                if (\class_exists($class)) {
                    $listener = new $class($this->app);
                    $event->register($listener->getName(), [$listener, 'handler']);
                }
            }
        }
    }

    protected function regEvents(Event $event, Config $config)
    {
        $cevents = $config->get("event.events");

        foreach ($cevents as $key => $cevent) {
            if (\class_exists($cevent)) {
                $listener = new $cevent($this->app);
                $event->register($listener->getName(), [$listener, 'handler']);
            }
        }
    }
}
