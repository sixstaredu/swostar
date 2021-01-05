<?php
namespace SwoStar\Container;

use Closure;
use Exception;

class Container
{
    protected static $instance;
    protected $bindings = [];
    protected $instances = [];
    /**
     * 容器绑定的方法
     * 六星教育 @shineyork老师
     * @param  string $abstract 标识
     * @param  object $object   实例对象或者闭包
     */
    public function bind($abstract, $object)
    {
        // 标识要绑定
        // 1. 就是一个对象
        // 2. 闭包的方式
        // 3. 类对象的字符串 (类的地址)
        $this->bindings[$abstract] = $object;
    }

    /**
     * 从容器中解析实例对象或者闭包
     * 六星教育 @shineyork老师
     * @param  string $abstract   标识
     * @param  array  $parameters 传递的参数
     * @return object             是一个闭包或者对象
     */
    public function make($abstract, $parameters = [])
    {
        return $this->resolve($abstract, $parameters);
    }

    protected function resolve($abstract, $parameters = [])
    {
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        if (!$this->has($abstract)) {
            // 如果不存在自行
            // 选择返回, 可以抛出一个异常
            // throw new Exception('没有找到这个容器对象'.$abstract, 500);
            $object = $abstract;
        } else {
            $object = $this->bindings[$abstract];
        }
        // 在这个容器中是否存在
        // 1. 判断是否一个为对象
        // 2. 闭包的方式
        if ($object instanceof Closure) {
            return $object();
        }

        // 3. 类对象的字符串 (类的地址)
        return $this->instances[$abstract] = (is_object($object)) ? $object :  new $object(...$parameters) ;
    }
    // 判断是否在容器中
    // 1. 容器很多便于扩展
    // 2. 可能在其他场景中会用到
    public function has($abstract)
    {
        return isset($this->bindings[$abstract]);
    }
    // 单例创建
    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }
        return static::$instance;
    }

    public static function setInstance($container = null)
    {
        return static::$instance = $container;
    }
}
