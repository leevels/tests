<?php
/*
 * This file is part of the ************************ package.
 * ##########################################################
 * #   ____                          ______  _   _ ______   #
 * #  /     \       ___  _ __  _   _ | ___ \| | | || ___ \  #
 * # |   (  ||(_)| / _ \| '__|| | | || |_/ /| |_| || |_/ /  #
 * #  \____/ |___||  __/| |   | |_| ||  __/ |  _  ||  __/   #
 * #       \__   | \___ |_|    \__  || |    | | | || |      #
 * #     Query Yet Simple      __/  |\_|    |_| |_|\_|      #
 * #                          |___ /  Since 2010.10.03      #
 * ##########################################################
 *
 * The PHP Framework For Code Poem As Free As Wind. <Query Yet Simple>
 * (c) 2010-2018 http://queryphp.com All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace queryyetsimple\view;

use RuntimeException;

/**
 * html 模板处理类
 *
 * @author Xiangmin Liu <635750556@qq.com>
 * @package $$
 * @since 2016.11.18
 * @version 1.0
 */
class html extends aconnect implements iconnect
{

    /**
     * 视图分析器
     *
     * @var \queryyetsimple\view\iparser
     */
    protected $parser;

    /**
     * 解析 parse
     *
     * @var callable
     */
    protected static $parseResolver;

    /**
     * 配置
     *
     * @var array
     */
    protected $option = [
        'development' => false,
        'controller_name' => 'index',
        'action_name' => 'index',
        'controlleraction_depr' => '_',
        'theme_name' => 'default',
        'theme_path' => '',
        'theme_path_default' => '',
        'suffix' => '.html',
        'theme_cache_path' => '',
        'cache_lifetime' => 2592000
    ];

    /**
     * 加载视图文件
     *
     * @param string $file 视图文件地址
     * @param array $vars
     * @param boolean $display 是否显示
     * @param string $ext 后缀
     * @return string
     */
    public function display(string $file = null, array $vars = [], string $ext = '', bool $display = true)
    {
        // 加载视图文件
        $file = $this->parseDisplayFile($file, $ext);

        // 变量赋值
        if ($vars) {
            $this->setVar($vars);
        }
        
        if (is_array($this->vars) && ! empty($this->vars)) {
            extract($this->vars, EXTR_PREFIX_SAME, 'q_');
        }

        $cachepath = $this->getCachePath($file); // 编译文件路径

        if ($this->isCacheExpired($file, $cachepath)) { // 重新编译
            $this->parser()->doCombile($file, $cachepath);
        }

        // 返回类型
        if ($display === false) {
            ob_start();
            include $cachepath;
            $result = ob_get_contents();
            ob_end_clean();

            return $result;
        } else {
            include $cachepath;
        }
    }

    /**
     * 设置 parse 解析回调
     *
     * @param callable $parseResolver
     * @return void
     */
    public static function setParseResolver(callable $parseResolver)
    {
        static::$parseResolver = $parseResolver;
    }

    /**
     * 解析 parse
     *
     * @return \queryyetsimple\view\iparser
     */
    public function resolverParser()
    {
        if (! static::$parseResolver) {
            throw new RuntimeException('Html theme not set parse resolver');
        }
        return call_user_func(static::$parseResolver);
    }

    /**
     * 获取分析器
     *
     * @return \queryyetsimple\view\iparser
     */
    public function parser()
    {
        if (! is_null($this->parser)) {
            return $this->parser;
        }
        return $this->parser = $this->resolverParser();
    }

    /**
     * 获取编译路径
     *
     * @param string $file
     * @return string
     */
    protected function getCachePath(string $file)
    {
        if (! $this->getOption('theme_cache_path')) {
            throw new RuntimeException('Theme cache path must be set');
        }

        // 统一斜线
        $file = str_replace('//', '/', str_replace('\\', '/', $file));

        // 统一缓存文件
        $file = basename($file, '.' . pathinfo($file, PATHINFO_EXTENSION)) . '.' . md5($file) . '.php';

        // 返回真实路径
        return $this->getOption('theme_cache_path') . '/' . $file;
    }

    /**
     * 判断缓存是否过期
     *
     * @param string $file
     * @param string $cachepath
     * @return boolean
     */
    protected function isCacheExpired(string $file, string $cachepath)
    {
        // 开启调试
        if ($this->getOption('development')) {
            return true;
        }

        // 缓存文件不存在过期
        if (! is_file($cachepath)) {
            return true;
        }

        // 编译过期时间为 <= 0 表示永不过期
        if ($this->getOption('cache_lifetime') <= 0) {
            return false;
        }

        // 缓存时间到期
        if (filemtime($cachepath) + intval($this->getOption('cache_lifetime')) < time()) {
            return true;
        }

        // 文件有更新
        if (filemtime($file) >= filemtime($cachepath)) {
            return true;
        }

        return false;
    }
}