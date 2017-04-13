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

// Url.php
// 2017/3/19

namespace core;

class Url
{
    /**
     * 生成一个url地址
     */
    static public function get($url, $params = '')
    {
        $protocol = isHttps() ? 'https://' : 'http://';
        $protocol .= $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . '/';
        //$protocol = str_ireplace('index.php', '', $protocol);
        if (is_string($url)) {
            $url = explode('/', $url);
            $count = count($url);
            switch ($count) {
                case 3:
                    $url = $url[0] . '/' . $url[1] . '/' . $url[2];
                    break;
                case 2:
                    $url = MODE . '/' . $url[0] . '/' . $url[1];
                    break;
                case 1:
                    $url = MODE . '/' . CONTROL . '/' . $url[0];
                    break;
            }
            $url = $protocol . $url . '/';
        }
        if (!empty($params)) {
            $_params = '';
            foreach ($params as $key => $value) {
                $_params .= $key . '/' . $value;
                //$_GET[$key] = $value;
            }
        }
        $url .= $_params;
        return $url;
    }
}