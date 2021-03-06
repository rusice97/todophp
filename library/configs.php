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

// configs.php
// 2017/3/18

return [
    // +----------------------------------------------------------------------
    // | 模板设置
    // +----------------------------------------------------------------------
    'template' => [
        'template_suffix' => '.html',
        'template_cache' => RUNTIME . '_cache' . DIRECTORY_SEPARATOR,
        'tpl_begin' => '{todo:',
        'tpl_end' => '}'
    ],
    // +----------------------------------------------------------------------
    // | 缓存设置
    // +----------------------------------------------------------------------
    'cache' => [
        'type' => 'memcache',
        'hostname' => '127.0.0.1',
        'hostport' => 11211,
        'prefix' => 'hi_',
        'expire' => 3600
    ],
    'url' => [
        'var_pathinfo' => 's',
        'route_on' => true,
        'url_domain' => ''
    ],
    'extend_fun' => [
        'template_fun'
    ]
];