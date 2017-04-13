<?php
// +----------------------------------------------------------------------
// | Hiblog [ A SIMPLE PHP BLOG ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 http://ruiblog.top All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: RuiZhaoLi <liruizhao1997@outlook.com>
// +----------------------------------------------------------------------

// boot.php
// 2017/3/18
if (!defined('ROOT')) die('Illegal access');
$GLOBALS['BlogStart'] = microtime(true);
// 设置时区
date_default_timezone_set('Asia/Shanghai');
error_reporting(E_ALL & ~E_NOTICE); // TODO::报告所有错误,开发阶段结束需删除
define('DEBUG', true);
define('VERSION', '1.0');
define('LIBS_PATH', ROOT . 'library' . DIRECTORY_SEPARATOR);
define('EXT', LIBS_PATH . 'exts' . DIRECTORY_SEPARATOR);
define('BLOGMODE', 'Common');
define('MODEPATH', LIBS_PATH . 'mode' . DIRECTORY_SEPARATOR);
define('APPPATH', ROOT . 'application' . DIRECTORY_SEPARATOR);
define('UPLOADPATH', ROOT . 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR);
define('RUNTIME', ROOT . '_runtime' . DIRECTORY_SEPARATOR);
define('DEFAULT_MODE', 'home');
define('DEFAULT_CONTROL', 'Index');
define('DEFAULT_METHOD', 'index');
define('CONTROL_EXT', 'control');
define('MODEL_EXT', 'Model');
define('VERDOEPATH', LIBS_PATH . 'verdor' . DIRECTORY_SEPARATOR);

define('ISPOST', $_SERVER['REQUEST_METHOD'] == 'POST' ? true : false);
define('ISWIN', substr(PHP_OS, 0, 3) == 'WIN' ? true : false);

if (ini_get('magic_quotes_gpc')) {
    function stripslashesRecursive(array $array)
    {
        foreach ($array as $k => $v) {
            if (is_string($v)) {
                $array[$k] = stripslashes($v);
            } else if (is_array($v)) {
                $array[$k] = stripslashesRecursive($v);
            }
        }
        return $array;
    }

    $_GET = stripslashesRecursive($_GET);
    $_POST = stripslashesRecursive($_POST);
}

/*session初始化*/
ini_set('session.use_trans_sid', 0);
ini_set('session.use_cookies', 1);
ini_set('session.use_only_cookies', 1);
session_start();
session_name('HBSESSION');

// 设置上传文件的临时目录
if (!file_exists(RUNTIME . '_temp' . DIRECTORY_SEPARATOR)) {
    mkdir(RUNTIME . '_temp' . DIRECTORY_SEPARATOR, 0777, true);
}
// 自动获取应用名
$appName = rtrim(APPPATH, DIRECTORY_SEPARATOR);
$rpos = strrpos($appName, DIRECTORY_SEPARATOR);
$temp = substr($appName, $rpos + 1);
$temp = explode('/', $temp);
define('APP', $temp[1]);
unset($appName, $temp);

/**
 * 自动加载
 */
include VERDOEPATH . 'autoload.php';