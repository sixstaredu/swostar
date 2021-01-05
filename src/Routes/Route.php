<?php
namespace SwoStar\Routes;

use SwoStar\Console\Input;

class Route
{
    protected static $instance = null;
    // 路由本质实现是会有一个容器在存储解析之后的路由
    protected $routes = [];

    // 定义了访问的类型
    protected $verbs = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'];
    // 记录路由的文件地址
    protected $map = [];
    // 记录请求的方式
    // 语文叫铺垫 =》 websocket
    protected $method = null;

    protected $flag = null;

    protected $namespace = null;

    protected function __construct($map)
    {
        $this->map = $map;
    }

    public static function getInstance($map)
    {
        if (\is_null(self::$instance)) {
            self::$instance = new static($map);
        }
        return self::$instance ;
    }

    public function get($uri, $action)
    {
        return $this->addRoute(['GET'], $uri, $action);
    }

    public function post($uri, $action)
    {
        return $this->addRoute(['POST'], $uri, $action);
    }

    public function any($uri, $action)
    {
        return $this->addRoute(self::$verbs, $uri, $action);
    }

    public function wsController($uri, $controller)
    {
        $actions = [
          'open',
          'message',
          'close',
        ];
        foreach ($actions as $key => $action) {
            $this->addRoute([$action], $uri, $controller.'@'.$action);
        }
    }
    /**
     * 注册路由
     * 六星教育 @shineyork老师
     */
    protected function addRoute($methods, $uri, $action)
    {
        foreach ($methods as $method ) {
            if ($action instanceof \Closure) {
                $this->routes[$this->flag][$method][$uri] = $action;
            } else {
                $this->routes[$this->flag][$method][$uri] = $this->namespace."\\".$action;
            }
        }
        return $this;
    }
    /**
     * 根据请求校验路由，并执行方法
     * 六星教育 @shineyork老师
     * @return [type] [description]
     */
    public function match($path, $param = [])
    {
        $action = null;

        foreach ($this->routes[$this->flag][$this->method] as $uri => $value) {
            $uri = ($uri && substr($uri,0,1)!='/') ? "/".$uri : $uri;

            if ($path === $uri) {
                $action = $value;
                break;
            }
        }

        if (!empty($action)) {
            return $this->runAction($action, $param);
        }

        Input::info('没有找到方法', $path);

        return "404";
    }

    private function runAction($action, $param = null)
    {
        if ($action instanceof \Closure) {
            return $action(...$param);
        } else {
            // 控制器解析
            $arr = \explode("@", $action);
            $class = new $arr[0]();
            return $class->{$arr[1]}(...$param);
        }
    }

    public function registerRoute()
    {
        foreach ($this->map as $key => $route) {
            $this->flag = $route['flag'];
            $this->namespace = $route['namespace'];
            require_once $route['path'];
        }
        return $this;
    }

    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    public function setFlag($flag)
    {
        $this->flag = $flag;
        return $this;
    }
    public function getRoutes()
    {
        return $this->routes;
    }
}
