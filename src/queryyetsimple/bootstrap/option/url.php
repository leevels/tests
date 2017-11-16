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
 * (c) 2010-2017 http://queryphp.com All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * url 默认配置文件
 *
 * @author Xiangmin Liu <635750556@qq.com>
 * @package $$
 * @since 2016.11.19
 * @version 1.0
 */
return [

    /**
     * ---------------------------------------------------------------
     * url 模式
     * ---------------------------------------------------------------
     *
     * default = 普通，pathinfo = pathinfo 模式
     */
    'model' => 'pathinfo',

    /**
     * ---------------------------------------------------------------
     * 是否开启重写
     * ---------------------------------------------------------------
     *
     * 开启了重写将会去掉 生成的 url 中的入口文件 index.php
     */
    'rewrite' => false,

    /**
     * ---------------------------------------------------------------
     * url 分割符
     * ---------------------------------------------------------------
     *
     * 你也可以设置为 “-”等，不能设置特殊字符如“=&”等
     */
    'pathinfo_depr' => '/',

    /**
     * ---------------------------------------------------------------
     * 伪静态后缀
     * ---------------------------------------------------------------
     *
     * 系统进行路由解析时将会去掉后缀后然后继续执行 url 解析
     */
    'html_suffix' => '.html',

    /**
     * ---------------------------------------------------------------
     * 缓存路由
     * ---------------------------------------------------------------
     *
     * 设置路由解析后，系统会将所有路由一并缓存到一个文件中免去分析开销从而提高系统运行性能
     */
    'router_cache' => true,

    /**
     * ---------------------------------------------------------------
     * 严格 router 匹配模式
     * ---------------------------------------------------------------
     *
     * 是否启用严格 router 匹配模式,使用启用严格匹配，路由匹配正则结尾会加入 $ 标志
     */
    'router_strict' => false,

    /**
     * ---------------------------------------------------------------
     * 是否开启域名路由解析
     * ---------------------------------------------------------------
     *
     * 开启域名解析路由会首先去尝试匹配域名中是否存在路由
     */
    'router_domain_on' => true,

    /**
     * ---------------------------------------------------------------
     * 顶级域名
     * ---------------------------------------------------------------
     *
     * 例如 queryphp.com，用于路由解析以及 \queryyetsimple\router\router::url 生成
     */
    'router_domain_top' => '',

    /**
     * ---------------------------------------------------------------
     * url 生成是否开启子域名
     * ---------------------------------------------------------------
     *
     * 开启 url 子域名功能，用于 \queryyetsimple\router\router::url 生成
     */
    'make_subdomain_on' => false,

    /**
     * ---------------------------------------------------------------
     * public　资源地址
     * ---------------------------------------------------------------
     *
     * 设置公共资源 url 地址
     */
    'public' => env('url_public', 'http://public.foo.bar'),

    /**
     * ---------------------------------------------------------------
     * pathinfo 是否自动 restinfo 解析
     * ---------------------------------------------------------------
     *
     * 当系统路由匹配失败后将会进行 pathinfo 解析
     * 系统可以为 restful 提供智能解析
     *
     * @see \queryyetsimple\router\router::pathinfoRestful
     */
    'pathinfo_restful' => true,

    /**
     * ---------------------------------------------------------------
     * pathinfo 是否自动 restinfo 解析
     * ---------------------------------------------------------------
     *
     * 系统进行 pathinfo 解析时将存在这个数组中的值放入参数
     * 其中如果是数字系统默认也会放进去
     * see \queryyetsimple\router\router:parsePathInfo
     */
    'args_protected' => [],

    /**
     * ---------------------------------------------------------------
     * args 匹配正则
     * ---------------------------------------------------------------
     *
     * 系统进行 pathinfo 解析时将匹配数据放入参数
     * regex 是对 args_protected 的一种补充
     * 例如:['[0-9]+', '[a-z]+']
     * see \queryyetsimple\router\router::parsePathInfo
     */
    'args_regex' => [],

    /**
     * ---------------------------------------------------------------
     * 严格 args 匹配模式
     * ---------------------------------------------------------------
     *
     * 是否启用严格 args 匹配模式,使用启用严格匹配，参数匹配正则结尾会加入 $ 标志
     * see \queryyetsimple\router\router::parsePathInfo
     */
    'args_strict' => false,

    /**
     * ---------------------------------------------------------------
     * 严格中间件匹配模式
     * ---------------------------------------------------------------
     *
     * 是否启用严格中间件匹配模式,使用启用严格匹配，参数匹配正则结尾会加入 $ 标志
     * see \queryyetsimple\router\router::getMiddleware
     */
    'middleware_strict' => false,

    /**
     * ---------------------------------------------------------------
     * 严格 HTTP 方法匹配模式
     * ---------------------------------------------------------------
     *
     * 是否启用严格 HTTP 方法匹配模式,使用启用严格匹配，参数匹配正则结尾会加入 $ 标志
     * see \queryyetsimple\router\router::getMethod
     */
    'method_strict' => false,

    /**
     * ---------------------------------------------------------------
     * 模板控制器目录
     * ---------------------------------------------------------------
     *
     * 系统指定的模板控制器目录，建议不用更改
     * see \queryyetsimple\router\router::parseDefaultBind
     */
    'controller_dir' => 'app\controller'
];
