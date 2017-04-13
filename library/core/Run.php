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
// 2017/3/18

namespace core;

class Run
{
    static $urlParams = [];
    protected $composer = [];

    /**
     * 加载应用模式
     */
    static private function loadMode()
    {
        $mode_file = MODEPATH . BLOGMODE . '.php';
        // 加载错误错误类
        if (DEBUG) {
            ini_set('display_errors', 'on');
            $whoops = new \Whoops\Run;
            $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
            $whoops->register();
        } else {
            ini_set('display_errors', 'off');
        }
        if (!file_exists($mode_file)) {
            header('Content-Type:text/html;charset=utf-8');
            die("应用模式文件{$mode_file}不存在!");
        } else {
            $files = require $mode_file;
            foreach ($files as $group => $file) {
                foreach ($file as $_file) {
                    if (file_exists($_file)) {
                        $file = include $_file;
                        if ($group == 'configs') {
                            Configs::set($file);
                        }
                    }
                }
            }
            return false;
        }
    }

    /**
     * 系统运行
     */
    static public function init()
    {
        self::loadMode();
        $urlParams = Dispath::dispather();
        extract($urlParams);
        $controlNamespace = $mode . '\\' . CONTROL_EXT . '\\' . $control;
        $controler = new $controlNamespace();
        $controler->$method($params);

    }

    /**
     * 提示页面显示
     * @param  string $redirectType 提示页面的方式 可选 JSON PAGE
     * @param  string $info 提示的信息
     * @param  string $url 转跳的URL地址
     * @param  integer $status 状态 主要用户json返回结果的判断
     * @param  integer $time 停留时间
     * @return null
     */
    static public function redirect($redirectType, $info, $url = '', $status = 1, $time = 3)
    {
        if ($redirectType == 'ajax') {
            header('Content-type: application/json'); //RFC 4672
            $ajax['info'] = $info;
            $ajax['status'] = $status;
            $ajax['info2'] = $status == 1 ? 'success' : 'fail';
            $ajax['url'] = $url;
            echo json_encode($ajax, true);
        } elseif ($redirectType == 'page') {
            ob_start();
            $referer = &$url;
            $_time = $status == 1 ? 1 : 3;
            include LIBS_PATH . 'tpl/info.html';
            ob_get_flush();
        }
        exit;
    }
}