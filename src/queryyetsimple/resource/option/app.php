<?php
// [$QueryPHP] A PHP Framework Since 2010.10.03. <Query Yet Simple>
// ©2010-2017 http://queryphp.com All rights reserved.
<<<queryphp
##########################################################
#   ____                          ______  _   _ ______   #
#  /     \       ___  _ __  _   _ | ___ \| | | || ___ \  #
# |   (  ||(_)| / _ \| '__|| | | || |_/ /| |_| || |_/ /  #
#  \____/ |___||  __/| |   | |_| ||  __/ |  _  ||  __/   #
#       \__   | \___ |_|    \__  || |    | | | || |      #
#     Query Yet Simple      __/  |\_|    |_| |_|\_|      #
#                          |___ /  Since 2010.10.03      #
##########################################################
queryphp;

/**
 * 应用全局配置文件
 *
 * @author Xiangmin Liu<635750556@qq.com>
 * @package $$
 * @since 2016.11.19
 * @version 1.0
 */
return [ 
        
        /**
         * ---------------------------------------------------------------
         * 自定义命名空间 （ 名字 = 入口路径）
         * ---------------------------------------------------------------
         *
         * 你可以在这里设置你应用程序的自定义命名空间
         * 相关文档请访问 [执行流程.MVC\命名空间与自动载入.Namespace.Autoload]
         * see https://github.com/hunzhiwange/document/blob/master/execution-flow/namespace-and-autoload.md
         */
        'namespace' => [ ],
        
        /**
         * ---------------------------------------------------------------
         * 默认应用
         * ---------------------------------------------------------------
         *
         * 默认应用非常重要，与路由解析息息相关
         */
        'default_app' => 'home',
        
        /**
         * ---------------------------------------------------------------
         * 默认控制器
         * ---------------------------------------------------------------
         *
         * 未指定的控制器，此时会默认指定为此控制器
         */
        'default_controller' => 'index',
        
        /**
         * ---------------------------------------------------------------
         * 默认方法
         * ---------------------------------------------------------------
         *
         * 未指定的方法，此时会默认指定为此方法
         */
        'default_action' => 'index',
        
        /**
         * ---------------------------------------------------------------
         * 应用提供者
         * ---------------------------------------------------------------
         *
         * 这里的服务提供者为类的名字，例如 home\infrastructure\provider\test
         * 每一个服务提供者必须包含一个 register 方法，还可以包含一个 bootstrap 方法
         * 系统所有 register 方法注册后，bootstrap 才开始执行以便于调用其它服务提供者 register 注册的服务
         * 相关文档请访问 [系统架构\应用服务提供者]
         * see https://github.com/hunzhiwange/document/blob/master/system-architecture/service-provider.md
         */
        'provider' => [ ],
        
        /**
         * ---------------------------------------------------------------
         * 具有缓存功能的应用服务提供者
         * ---------------------------------------------------------------
         *
         * 这里的服务提供者严格意义上是服务提供者包，例如 log,http，系统会自动合并 queryyetsimple/log/provider 目录下面的 ['register.php', 'bootstrap.php']
         * register 为预先注册服务提供者，bootstrap 为系统注册完毕所有服务提供者后再注册其它服务
         * 正如其名具有缓存功能的服务提供者，他们会被自动缓存到 {缓存目录}/provider 下面,并且有两组一组是应用服务提供者，一组为系统服务提供者
         */
        'provider_with_cache' => [ ],
        
        /**
         * ---------------------------------------------------------------
         * 默认配置扩展文件
         * ---------------------------------------------------------------
         *
         * 系统默认包含app,cache,console,cookie,database,debug,i18n,log,queue,session,url,view,router，你也可以扩展自己的应用的配置
         * 注意：配置扩展文件不会覆盖系统默认配置文件
         */
        'option_extend' => [ ],
        
        /**
         * ---------------------------------------------------------------
         * 路由扩展文件
         * ---------------------------------------------------------------
         *
         * 路由配置文件为一个数组，路由默认有一个文件 router.php
         * 如果设置为 ['test1', 'test2'],路由文件 router_test1.php,router_test2.php 作为路由文件可以被载入
         * 注意：路由配置文件不会覆盖系统配置文件及其扩展文件
         */
        'router_extend' => [ ],
        
        /**
         * ---------------------------------------------------------------
         * Gzip 压缩
         * ---------------------------------------------------------------
         *
         * 启用页面 gzip 压缩，需要系统支持 gz_handler 函数
         */
        'start_gzip' => true,
        
        /**
         * ---------------------------------------------------------------
         * 系统时区
         * ---------------------------------------------------------------
         *
         * 此配置用于 date_default_timezone_set 应用设置系统时区
         * 此功能会影响到 date.time 相关功能
         */
        'time_zone' => 'Asia/Shanghai',
        
        /**
         * ---------------------------------------------------------------
         * 安全 key
         * ---------------------------------------------------------------
         *
         * 请妥善保管此安全 key,防止密码被人破解
         * queryyetsimple\encryption:authcode 安全 key
         */
        'q_auth_key' => 'queryphp-872-028-111-222-sn7i',
        
        /**
         * ---------------------------------------------------------------
         * 文件上传保存文件名函数
         * ---------------------------------------------------------------
         *
         * 默认应用非常重要，与路由解析息息相关
         */
        'upload_file_rule' => 'time',
        
        /**
         * ---------------------------------------------------------------
         * 系统所有应用
         * ---------------------------------------------------------------
         *
         * 系统在运行过程中会自动缓存 {项目}/application 下面的目录
         * 这个缓存将会用于注册命名空间以及用于路由解析
         */
        '~apps~' => [ ] 
]; 
