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

// SmartyBoot.php
// 2017/4/8

namespace core;

class SmartyBoot
{
    private $_smarty;
    static $_configs;

    public function __construct()
    {
        $this->_smarty = new \Smarty();
        if (self::$_configs == null) {
            self::$_configs = Configs::get('VIEW');
        }
        //$this->_smarty->template_dir = "./templates"; //模板存放目录
//        $this->_smarty->debugging = DEBUG;
        $this->_smarty->compile_dir = self::$_configs['CACHE_PATH']; //编译目录
        $this->_smarty->left_delimiter = self::$_configs['LEFT_D']; //左定界符
        $this->_smarty->right_delimiter = self::$_configs['RIGHT_D']; //右定界符
        $this->_smarty->registerPlugin('function', '_include', 'myInclude');
        $this->_smarty->registerPlugin('function', 'urlecho', 'url');
        $this->_smarty->registerPlugin('function', 'load_static_file', 'load_static_file');
    }

    public function assign($tpl_var, $value = null, $nocache = false)
    {
        $this->_smarty->assign($tpl_var, $value, $nocache);
    }

    public function display($template = '', $cache_id = null, $compile_id = null, $parent = null)
    {
        if (!is_dir($this->_smarty->compile_dir)) {
            mkdir($this->_smarty->compile_dir);
        } elseif (!is_writeable($this->_smarty->compile_dir))
            exit('缓存目录不可写!');
        $template .= self::$_configs['TEMP_EXT'];
        $template = str_replace('/', DIRECTORY_SEPARATOR, $template);
        if (!file_exists($template)) exit('模板' . $template . '不存在');
        return $this->_smarty->display($template, $cache_id, $compile_id, $parent);
    }
}