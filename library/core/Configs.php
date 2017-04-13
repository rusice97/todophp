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

// Conf.php
// 2017/3/18

namespace core;

class Configs
{
    static $configs = array();

    /**
     * 系统配置加载
     * @param $configs
     * @return bool
     */
    static public function set($configs)
    {
        if (is_array($configs) === false) return false;
        foreach ($configs as $config_group => $config) {
            if ($config_group == 'EXTEND_FUN') {
                // load the extend functions
                foreach ($config as $ext) {
                    if (file_exists(EXT . $ext . '.php'))
                        include EXT . $ext . '.php';
                    else
                        throw new \Exception('扩展文件' . $ext . '不存在');
                }
            }
            $config_group = strtoupper($config_group);
            if (is_array($config_group)) {
                foreach ($config as $key => $value) {
                    self::$configs[$config_group][$key] = $value;
                }
            } else {
                self::$configs[$config_group] = $config;
            }
        }
    }

    /**
     * 系统配置读取
     * @param $key
     * @return bool|mixed
     */
    static public function get($key = '')
    {
        if (empty($key)) return self::$configs;
        $key = strtoupper($key);
        if (strpos($key, '.')) {
            $key = explode('.', $key);
            return isset(self::$configs[$key[0]][$key[1]]) ? self::$configs[$key[0]][$key[1]] : false;
        }
        return isset(self::$configs[$key]) ? self::$configs[$key] : false;
    }

}