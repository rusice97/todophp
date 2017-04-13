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

// Control.php
// 2017/4/11

namespace core;

class Control
{
    public function display($content)
    {
        header('Content-type:text/html;charset=utf-8');
        echo $content;
    }

    public function fetch()
    {

    }

    public function assign()
    {

    }

    public function success()
    {

    }

    public function error()
    {

    }

    public function redirect()
    {

    }
}