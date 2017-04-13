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

// Common.php
// 2017/3/18

if (!defined('ROOT')) die('Illegal access');

return array(
    'configs' => [
        LIBS_PATH . 'configs.php',
        APPPATH . 'configs.php',
        APPPATH . 'database.php'
    ],
    'functions' => [
        LIBS_PATH . 'functions.php',
        APPPATH . 'functions.php'
    ]
);