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

// Dispath.php
// 2017/4/11

namespace core;


class Dispath
{
    static $urlParams = [];

    /**
     * URL映射操作
     */
    static public function dispather()
    {
        $config = Configs::get('url');
        // 自动检查是否开启兼容模式
        if (isset($_GET[$config['var_pathinfo']])) $_SERVER['PATH_INFO'] = $_GET[$config['var_pathinfo']];
        unset($_GET[$config['var_pathinfo']]);

        if (empty($_SERVER['PATH_INFO'])) {
            define('__INFO__', '');
        } else {
            define('__INFO__', trim($_SERVER['PATH_INFO'], '/'));
        }

        $uri = trim(__INFO__, '/');
        $pos = strrpos($uri, '?');
        if ($pos && $pos + 1 < strlen($uri)) {
            // 存在 ? 且?不在最后一位 为get
            $getParams = substr($uri, $pos + 1);
            // 防止像 http://localhost/mvc/Admin/Article?id=1&&&&&&&&&&&&&&&cid=1 这种恶意传参
            $getParams = array_filter(explode('&', $getParams));
            array_map('self::param', $getParams);
            // $_GET 赋值完成后,可删除
            $uri = substr($uri, 0, $pos);
        }
        $request_uri = explode('/', $uri);
        self::analysis($request_uri);
        if (isset(self::$urlParams['mode']) && !empty(self::$urlParams['mode'])) {
            $mode = self::$urlParams['mode'];
        } else {
            $mode = DEFAULT_MODE;
        }
        if (isset(self::$urlParams['control']) && !empty(self::$urlParams['control'])) {
            $control = self::$urlParams['control'];
        } else {
            $control = DEFAULT_CONTROL;
        }
        if (isset(self::$urlParams['method']) && !empty(self::$urlParams['method'])) {
            $method = self::$urlParams['method'];
        } else {
            $method = DEFAULT_METHOD;
        }
        if (isset(self::$urlParams['params'])) {
            $params = self::$urlParams['params'];
        } else {
            $params = null;
        }
        define('MODE', $mode);
        define('CONTROL', $control);
        define('METHOD', $method);
        return ['mode' => $mode, 'control' => $control, 'method' => $method];
    }

    /**
     * 解析url中的参数
     * @param $request_uri
     * @return bool
     */
    static private function analysis(&$request_uri)
    {
        // 获取模块名
        self::$urlParams['mode'] = empty($request_uri[0]) ? DEFAULT_MODE : $request_uri[0];
        self::$urlParams['mode'] = self::$urlParams['mode'];
        array_shift($request_uri);
        // 获取控制器名
        self::$urlParams['control'] = empty($request_uri[0]) ? DEFAULT_CONTROL : $request_uri[0];
        self::$urlParams['control'] = ucfirst(self::$urlParams['control']);
        array_shift($request_uri);
        // 获取方法
        self::$urlParams['method'] = empty($request_uri[0]) ? DEFAULT_METHOD : $request_uri[0];
        self::$urlParams['method'] = self::$urlParams['method'];
        array_shift($request_uri);
        // 获取参数
        if (!empty($request_uri[0])) {
            $i = 0;
            $count = count($request_uri);
            // 获取参数
            while ($i < $count) {
                if (isset($request_uri[$i + 1])) {
                    self::$urlParams['params'][$request_uri[$i]] = $request_uri[$i + 1];
                }
                $i += 2;
            }
            $_GET = self::$urlParams['params']; // 把参数赋值到$_GET中,方便获取
        }
        return false;
    }

    static private function param(&$value)
    {
        $params = explode('=', $value);
        $_GET[$params[0]] = $params[1];
    }
}