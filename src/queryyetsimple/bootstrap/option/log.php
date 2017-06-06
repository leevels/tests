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
 * 日志默认配置文件
 *
 * @author Xiangmin Liu<635750556@qq.com>
 * @package $$
 * @since 2016.11.19
 * @version 1.0
 */
return [ 
        
        /**
         * ---------------------------------------------------------------
         * 是否启用日志
         * ---------------------------------------------------------------
         *
         * 默认记录日志，记录日志会消耗服务器资源
         */
        'enabled' => true,
        
        /**
         * ---------------------------------------------------------------
         * 允许记录的日志级别
         * ---------------------------------------------------------------
         *
         * 随意自定义,其中 error、sql、debug 和 info 为系统内部使用
         */
        '+level' => [ 
                'error',
                'sql',
                'debug',
                'info' 
        ],
        
        /**
         * ---------------------------------------------------------------
         * 启用错误日志
         * ---------------------------------------------------------------
         *
         * 是否记录错误的日志信息，启用错误日志记录各种错误和异常
         */
        'error_enabled' => true,
        
        /**
         * ---------------------------------------------------------------
         * 启用 sql 日志
         * ---------------------------------------------------------------
         *
         * 是否记录系统中的 sql 日志，启用即可记录开发过程中的 sql 相关记录
         */
        'sql_enabled' => true,
        
        /**
         * ---------------------------------------------------------------
         * 日志文件大小限制
         * ---------------------------------------------------------------
         *
         * 日志大小的单位为字节 byte
         */
        'file_size' => 2097152,
        
        /**
         * ---------------------------------------------------------------
         * 日志文件名时间格式化
         * ---------------------------------------------------------------
         *
         * 默认按小时记录当前日志，可以根据你项目的大小设置日志的频度
         */
        'file_name' => 'Y-m-d H',
        
        /**
         * ---------------------------------------------------------------
         * 日志时间格式化
         * ---------------------------------------------------------------
         *
         * 每条日志信息开头的时间信息
         */
        'time_format' => '[Y-m-d H:i]',
        
        /**
         * ---------------------------------------------------------------
         * 默认路径
         * ---------------------------------------------------------------
         *
         * 默认的日志路径，如果没有则设置为 \queryyetsimple\mvc\project::bootstrap ()->path_cache_log
         */
        'path_default' => project ( 'path_cache_log' ) 
];
