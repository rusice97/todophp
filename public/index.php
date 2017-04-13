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

// index.php
// 2017/3/18
if (version_compare(PHP_VERSION, '5.4', '<')) die('PHP Version: PHP 5.4 or newer');

define('ROOT', __DIR__ . '/../');
require ROOT . 'library' . DIRECTORY_SEPARATOR . 'boot.php';

\core\Run::init();