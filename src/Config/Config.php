<?php

namespace SwoStar\Config;

class Config
{

    protected $itmes = [];

    function __construct($path)
    {
        // 读取配置
        $this->itmes = $this->phpParser($path);
    }
    /**
     * 读取PHP文件类型的配置文件
     * 六星教育 @shineyork老师
     * @return [type] [description]
     */
    protected function phpParser($path)
    {
        // 1. 找到文件
        // 此处跳过多级的情况
        $files = scandir($path);
        $data = null;
        // 2. 读取文件信息
        foreach ($files as $key => $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            // 2.1 获取文件名
            $filename = \stristr($file, ".php", true);
            // 2.2 读取文件信息
            $data[$filename] = include $path."/".$file;
        }

        // 3. 返回
        return $data;
    }
    // key.key2.key3
    public function get($keys)
    {
        $data = $this->itmes;
        foreach (\explode('.', $keys) as $key => $value) {
            $data = $data[$value];
        }
        return $data;
    }
}
