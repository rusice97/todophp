<?php
// +----------------------------------------------------------------------
// | TODOPHP [ A SIMPLE PHP FRAMEWORD ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 http://ruiblog.top All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: RuiZhaoLi <liruizhao1997@outlook.com>
// +----------------------------------------------------------------------

// Cache.php
// 2017/3/21

namespace core;

class Cache
{
    static private $configs = array();

    static private $needly;

    /**
     * 读取配置
     */
    static public function readConfig()
    {
        if (empty(self::$configs)) {
            self::$configs = Configs::get('CACHE');
        }
    }

    static public function addPrefix($key)
    {
        return self::$configs['C_PREFIX'] . $key;
    }

    /**
     * 连接缓存服务
     */
    static public function connectCache()
    {
        try {
            self::readConfig();
            if (is_null(self::$needly)) {
                self::$needly = new \Memcache();
            }
            self::$needly->connect(self::$configs['C_HOST'], self::$configs['C_PORT']);
        } catch (\Exception $e) {
            die('Memcache Error!' . $e->getMessage());
        }
    }

    /**
     * 读取缓存
     */
    static public function readCache($key)
    {
        self::connectCache();
        return self::$needly->get(self::addPrefix($key));
    }

    /**
     * 写入缓存
     */
    static public function writeCache($key, $value, $ttl = '')
    {

        self::connectCache();
        return self::$needly->set(self::addPrefix($key), $value, empty($ttl) ? self::$configs['C_TIME'] : $ttl);
    }

    /**
     * 删除缓存
     */
    static public function deleteCache($key, $time = 0)
    {
        self::connectCache();
        return self::$configs->delete(self::addPrefix($key), $time);
    }
}