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

// template_fun.php
// 2017/4/9

function load_static_file($params, $smarty)
{
    $html = '';
    extract($params);
    switch ($type) {
        case 'css':
            $html = '<link rel="stylesheet" type="text/css" href="@#__file">';
            break;
        case 'js':
            $html = '<script type="text/javascript" src="@#__file"></script>';
            break;
    }
    return str_replace('@#__file', $path, $html);
}