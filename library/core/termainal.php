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

// termainal.php
// 2017/4/11

namespace core;

class termainal
{
    /**
     * cgi模式初始化系统
     */
    public function terminal($init = '', $ext = '')
    {
        echo 'Please wait...';
        if (empty($init)) {
            // 检查是否需要初始化composer
            if (!file_exists('composer.json'))
                $this->_initComposer($ext);
            // 检查是否需要配置应用目录
            $this->_initDir();
            echo "success\nDon't forget run 'composer update' or 'composer install'\n";
        } else {
            if ($init == 'composer') {
                // 只初始化composer
                if ($this->_initComposer($ext))
                    echo "success\nDon't forget run 'composer update' or 'composer install'\n";
            } elseif ($init == 'app') {
                // 只初始化应用目录
                if ($this->_initDir()) echo 'success';
            }
        }
    }

    /**
     * 初始化应用目录
     */
    private function _initDir()
    {
        if (file_exists(APPPATH . 'build.php')) {
            $build = include APPPATH . 'build.php';
            foreach ($build as $mode => $dir) {
                if ($mode == '__file__') {
                    foreach ($dir as $file) {
                        file_put_contents(APPPATH . $file . '.php', "<?php \r\n");
                    }
                } else {
                    foreach ($dir as $dirname => $files) {
                        foreach ($files as $file) {
                            $filePath = APPPATH . $mode . '/' . $dirname . '/';
                            if (!is_dir($filePath)) mkdir($filePath, 0644, true);
                            $fileName = $filePath . $file . '.php';
                            $fileContent = "<?php\r\nnamespace $mode\\$dirname;\r\nclass $file\r\n{\r\n\r\n}";
                            file_put_contents($fileName, $fileContent);
                        }
                    }
                }

            }
            return true;
        } else {
            die('no build script, fail');
        }
    }

    /**
     * 初始化composer
     * @param array $composerext
     * @return bool
     */
    private function _initComposer(array $composerext)
    {
        // 屏蔽不能修改的部分
        unset(
            $composerext['name'],
            $composerext['license'],
            $composerext['author'],
            $composerext['description'],
            $composerext['type'],
            $composerext['minimum-stability']
        );
        $config = $this->composer['config'];
        $require = $this->composer['require'];
        $composer = [
            'name' => 'ristodo/todophp',
            'license' => 'Apache-2',
            'author' => ['name' => 'liruizhao', 'email' => 'liruizhao1997@outlook.com'],
            'description' => 'the php blog',
            'type' => 'project',
            'config' => $config,
            "require" => $require,
            "minimum-stability" => "dev",
            "repositories" => [
                "packagist" => [
                    "type" => "composer",
                    "url" => "https://packagist.phpcomposer.com"
                ]
            ]
        ];
        $composer = array_merge($composer, $composerext);
        file_put_contents('composer.json', str_replace('\/', '/', json_encode($composer, JSON_UNESCAPED_UNICODE)));
        return true;
    }
}