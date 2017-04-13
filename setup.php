#!/usr/bin/env php
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

// setup.php
namespace core;

define('ROOT', __DIR__ . '/');
require ROOT . 'library/boot.php';
include LIBS_PATH . 'core' . DIRECTORY_SEPARATOR . 'termainal.php';
$termainnal = new termainal();
/**
 * 额外composer设置
 */
$config = [
    'config' => [
        "vendor-dir" => str_replace([ROOT, DIRECTORY_SEPARATOR], [ '','/'], VERDOEPATH),
        "preferred-install" => "dist",
        "optimize-autoloader" => true,
        "discard-changes" => true
    ],
    'require' => [
        'smarty/smarty' => '~3.1',
        'filp/whoops' => '2.1.8'
    ],
    "autoload" => [
        "psr-4" => [
            "core\\" => str_replace([ROOT, DIRECTORY_SEPARATOR], [ '','/'], LIBS_PATH) . "core/",
        ],
        "classmap" => [
            "" . str_replace([ROOT, DIRECTORY_SEPARATOR], [ '','/'], APPPATH),
            str_replace([ROOT, DIRECTORY_SEPARATOR], [ '','/'], LIBS_PATH) . "core/"
        ]
    ]
];
/**
 * 初始化composer
 * @param $termainnal
 * @return mixed
 */
function initComposer($termainnal, $config)
{
    return $termainnal->terminal('composer', $config);
}

/**
 * 初始化app目录
 * @param $termainnal
 * @return mixed
 */
function initDir($termainnal)
{
    return $termainnal->terminal('app');
}

/**
 * 初始化全部
 * @param $termainnal
 * @param $config
 * @return mixed
 */
function initAll($termainnal, $config)
{
    return $termainnal->terminal('', $config);
}

$argv = $_SERVER['argv'];
if ($argv[1] == 'composer') return initComposer($termainnal, $config);
elseif ($argv[1] == 'app') return initDir($termainnal);
elseif (empty($argv[1]) || !isset($argv[1])) return initAll($termainnal, $config);
elseif ($argv[1] == '--help') {
    echo "php setup [type] \n";
    echo "app --initialize the application dir,and create the files in application/build.php \n";
    echo "composer --initialize composer.json";
} else echo "illegal command!If you need help, please run 'php setup --help'";