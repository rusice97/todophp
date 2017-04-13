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

// build.php
// 2017/4/11

if (!defined('ROOT')) die('Illegal access');
return [
    // 定义admin模块的自动生成
    'admin' => [
        'control' => ['Article', 'Media', 'Tool', 'System'],
    ],
    'home' => [
        'control' => ['Index', 'Page']
    ],
    'Common' => [
        'control' => ['AdminBase', 'HomeBase', 'AppBase', 'ToolBase'],
        'model' => ['Article', 'Media', 'Tool', 'System'],
        'libs' => [],
    ],
    // 生成公用文件
    '__file__' => ['config', 'database', 'route']
];