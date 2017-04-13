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

// functions.inc.php
// 2017/3/18

function dump($echo, $isEnd = false)
{
    echo '<pre>';
    var_dump($echo);
    echo '</pre>';
    if ($isEnd) die;
}

/**
 * 模板路径格式
 *
 * @param $tempName
 * @return string
 */
function compiler($tempName)
{
    $tempName = rtrim($tempName, ':');
    $_root = defined('TMPL_PATH') ? TMPL_PATH . MODE . DIRECTORY_SEPARATOR :
        APPPATH . MODE . DIRECTORY_SEPARATOR . '_view' . DIRECTORY_SEPARATOR;
    _markTag($_root);
    $tempPath = '';
    if (false !== $pos = strpos($tempName, ':')) {
        if ($pos == 0) {
            $tempPath = $_root . substr($tempName, $pos + 1);
        } else {
            $tempArr = explode(':', $tempName);
            switch (count($tempArr)) {
                case 2:
                    // 调用本模块指定控制下的指定操作模板
                    $tempPath = $_root . trim($tempArr[0]) . DIRECTORY_SEPARATOR . trim($tempArr[1]);
                    break;
                case 3:
                    // 调用指定模块指定控制下的指定操作模板
                    $_root = defined('TMPL_PATH') ? TMPL_PATH . trim($tempArr[0]) . DIRECTORY_SEPARATOR :
                        APPPATH . trim($tempArr[0]) . DIRECTORY_SEPARATOR . '_view' . DIRECTORY_SEPARATOR;
                    $tempPath = $_root . trim($tempArr[1]) . DIRECTORY_SEPARATOR . trim($tempArr[2]);
            }
        }
    } else {
        // 调用本模块本控制下的指定操作模板
        $_root = defined('TMPL_PATH') ? TMPL_PATH . MODE . DIRECTORY_SEPARATOR . CONTROL . DIRECTORY_SEPARATOR :
            APPPATH . MODE . DIRECTORY_SEPARATOR . '_view' . DIRECTORY_SEPARATOR . CONTROL . DIRECTORY_SEPARATOR;
        $tempPath = $_root . $tempName;
    }
    return $tempPath;
}

/**
 * 生成模板标签
 * @param $_root
 */
function _markTag($_root)
{
    if (defined('TMPL_PATH')) {
        $_root = str_replace('./', __ROOT__, $_root);
    } else {
        $_root = str_replace(APPPATH, __ROOT__ . APP . DIRECTORY_SEPARATOR, $_root);
    }
    $_root = str_replace(DIRECTORY_SEPARATOR, '/', $_root);
    define('templates_skin', $_root);
}

function myInclude($param, $smarty)
{
    $ext = \Core\Configs::get('VIEW.TEMP_EXT');
    $file = compiler($param['file']);
    $file .= $ext;
    if (!file_exists($file)) exit('将要加载的模板' . $file . '不存在!');
    include $file;
}

function returnMod($result, $errCode)
{
    if ($result !== false) {
        return ['errCode' => 0, 'errMsg' => $result];
    } else {
        return ['errCode' => $errCode, 'errMsg' => '操作失败!'];
    }
}

function getE($e)
{
    ob_start();
    include LIBS_PATH . 'tpl' . DIRECTORY_SEPARATOR . 'error.html';
    exit;
}

function isHttps()
{
    if (!isset($_SERVER['HTTPS'])) return false;
    if ($_SERVER['HTTPS'] === 1) {  //Apache
        return true;
    } elseif ($_SERVER['HTTPS'] === 'on') { //IIS
        return true;
    } elseif ($_SERVER['SERVER_PORT'] == 443) { //其他
        return true;
    }
    return false;
}

/**
 * 生成url
 * @param $url
 * @param string $params
 * @param bool $isEcho 是否输出地址
 * @return array|string
 */
function url($param, $smarty)
{
    $url = isset($param['url']) ? $param['url'] : null;
    $_params = isset($param['param']) ? $param['param'] : null;

    return \Core\Url::get($url, $_params);
}

/**
 * 获取模板路径
 * getT(':nav'); 获取当前模块下视图目录下的nav.html
 * getT('Common:nav'); 获取Common模块下视图目录下的nav.html 跨模块调用
 * . getC('VIEW.TEMP_EXT')
 */
function getT($path)
{
    $view_path = DIRECTORY_SEPARATOR . DEFAULT_VIEW . DIRECTORY_SEPARATOR;
    $pos = strpos($path, ':');
    if (false === $pos) {
        $dir = MODE . $view_path . $path;
    } elseif ($pos == 0) {
        // getT(':nav'); 获取当前模块下视图目录下的nav.html
        $dir = MODE . $view_path . substr($path, $pos + 1);
    } else {
        $dir = MODE . $view_path . substr($path, 0, $pos) . DIRECTORY_SEPARATOR . substr($path, $pos + 1);
    }
    $includeFile = str_replace(DIRECTORY_SEPARATOR, '/', APPPATH . $dir) . '.' . getC('VIEW.TEMP_EXT');
    if (!file_exists($includeFile)) die('模板文件' . $includeFile . '不存在');
    return $includeFile;
}

/**
 * 获取文件后缀名
 * @param string $fileName
 * @return string
 */
function hb_GetInfoTypeName($fileName)
{
    if (false !== $pos = strrpos($fileName, '.')) {
        $typeName = substr($fileName, $pos + 1);
    } else {
        return 'undefined';
    }
    return $typeName;
}

function hb_GetDistanceTimeOfNow($time)
{
    $time_difference = time() - $time;
    if ($time_difference < 60) {
        $str = '刚刚';
    } elseif ($time_difference < 60 * 60) {
        $diff = floor($time_difference / 60);
        $str = $diff . '分钟前';
    } elseif ($time_difference < 60 * 60 * 24) {
        $diff = floor($time_difference / (60 * 60));
        $str = $diff . '小时前';
    } elseif ($time_difference < 60 * 60 * 24 * 3) {
        $diff = floor($time_difference / (60 * 60 * 24));
        if ($diff == 1)
            $str = '昨天 ' . date('H:s:i', $time);
        else
            $str = '前天 ' . date('H:s:i', $time);
    } else {
        $str = date('Y-m-d H:s:i', $time);
    }
    return $str;

}

function getS($type, $value, $ttl = '')
{

    if (!class_exists('Memcache')) {
        die('环境不支持memcache!');
    }
    static $configs;
    static $needle;
    if (is_null($configs)) {
        $configs = \Core\Configs::get('CACHE');
    }
    if (is_null($needle)) {
        $needle = new Memcache();
    }
    try {
        $needle->connect($configs['C_HOST'], $configs['C_PORT']);
        switch ($type) {
            case 'set':
                // $value = ['id',1]
                $res = $needle->set($configs['C_PREFIX'] . $value[0], $value[1], MEMCACHE_COMPRESSED, empty($ttl) ? $configs['C_TIME'] : $ttl);
                break;
            case 'get':
                //$value = 'id'
                $res = $needle->get($configs['C_PREFIX'] . $value);
                break;
            case 'delete':
                $res = $needle->delete($configs['C_PREFIX'] . $value, empty($ttl) ? 0 : $ttl);
                break;
            case 'flush':
                $res = $needle->flush();
                break;
        }
        return $res;
    } catch (Exception $e) {
        die($e->getMessage());
    }
}

function getC($key)
{
    return \Core\Configs::get($key);
}

/*
 * 生成随机数
 */
function getRandChar($length, $isSign = true)
{
    $str = null;
    $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
    if ($isSign) $strPol .= '!@#$%^&*()_+';
    $max = strlen($strPol) - 1;

    for ($i = 0; $i < $length; $i++) {
        $str .= $strPol[rand(0, $max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
    }

    return $str;
}

/**
 * 获取用户IP地址
 * @param int $type
 * @param bool $adv
 * @return mixed
 */
function getIp($type = 0, $adv = false)
{
    $type = $type ? 1 : 0;
    static $ip = NULL;
    if ($ip !== NULL) return $ip[$type];
    if ($adv) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos = array_search('unknown', $arr);
            if (false !== $pos) unset($arr[$pos]);
            $ip = trim($arr[0]);
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u", ip2long($ip));
    $ip = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}

/**
 * 递归分类数据 获取html代码
 * @param $trees
 * @param int $level
 * @return string
 */
function dealTree($trees, $level = 0)
{
    foreach ($trees as $tree) {
        $isCantDel = $tree['id'] == 1 ? true : false;
        $html .= '<tr class="text-c">';
        $html .= '<td><input type="input" value="' . $tree['listorders'] . '" name="listorders"></td>';
        $html .= '<td class="text-l"><u style="cursor:pointer" class="text-primary" onClick="article_edit(\'查看\', \'' . $tree['id'] . '\')"
                                              title="查看">' . str_repeat('&nbsp;══', $level) . $tree['category_name'] . ' </u ></td > ';
        $html .= '<td > ' . $tree['category_remark'] . ' </td > ';
        $html .= '<td > ' . $tree['category_alias'] . ' </td > ';
        $html .= '<td class="td-status" ><span class="label label-success radius" > ' . $tree['count_artiles'] . ' </span ></td > ';
        $html .= '<td class="f-14 td-manage" > ';
        $html .= '<a style = "text-decoration:none" onClick = "category_fastEdit(' . $tree['id'] . ',\'' . $tree['pid'] . '\')" href = "javascript:;" title = "修改" ><i class="Hui-iconfont" >&#xe72a;</i></a>';
        if (!$isCantDel) $html .= '<a style="text-decoration:none" class="ml-5" onClick="category_del(' . $tree['id'] . ')" href="javascript:;" title="删除"><i class="Hui-iconfont">&#xe6e2;</i></a></td></tr>';
        if (isset($tree['child'])) {
            $html .= dealTree($tree['child'], $level + 1);
        }
    }
    return $html;
}

/**
 * 递归输出评论
 */
function dealComment($trees, $level = 0)
{
    foreach ($trees as $tree) {

    }
}

/*
 * 处理表字段名
 */
function delCol($col)
{
    return "`$col`";
}

/*
 * 处理表字段值
 */
function delVal($val)
{
    return "'{$val}'";
}


function getM()
{
    return \Core\DataBase::getInc();
}