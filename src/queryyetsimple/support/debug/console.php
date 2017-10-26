<?php
// [$QueryPHP] The PHP Framework For Code Poem As Free As Wind. <Query Yet Simple>
// ©2010-2017 http://queryphp.com All rights reserved.
namespace queryyetsimple\support\debug;

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

use queryyetsimple\filesystem\fso;

/**
 * 调试
 *
 * @author Xiangmin Liu <635750556@qq.com>
 * @package $$
 * @since 2017.04.05
 * @version 1.0
 */
class console {
    
    /**
     * 记录调试信息
     * SQL 记录，加载文件等等
     *
     * @return void
     */
    public static function trace() {
        // ajax 不调试
        if (isset ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) && 'xmlhttprequest' == strtolower ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ))
            return;
        
        $arrTrace = [ ];
        
        // LOGO
        $arrTrace [] = implode ( '\n', self::formatLogo () );
        
        // 日志
        foreach ( project ( 'log' )->get () as $strType => $arrTemp ) {
            $arrTrace [strtoupper ( $strType ) . '.LOG' . ' (' . count ( $arrTemp ) . ')'] = implode ( '\n', array_map ( function ($arrItem) {
                return static::formatMessage ( $arrItem );
            }, $arrTemp ) );
        }
        
        // 加载文件
        $arrInclude = get_included_files ();
        $arrTrace ['LOADED.FILE' . ' (' . count ( $arrInclude ) . ')'] = implode ( '\n', array_map ( function ($sVal) {
            return fso::tidyPathLinux ( $sVal );
        }, $arrInclude ) );
        
        ob_start ();
        include dirname ( dirname ( __DIR__ ) ) . '/bootstrap/template/trace.php';
        $sReturn = ob_get_contents ();
        ob_end_clean ();
        
        return $sReturn;
    }
    
    /**
     * 格式化日志信息
     *
     * @param array $arrItem            
     * @return string
     */
    protected static function formatMessage($arrItem) {
        return addslashes ( $arrItem [0] . ' ' . json_encode ( $arrItem [1], JSON_UNESCAPED_UNICODE ) );
    }
    
    /**
     * 格式化 LOGO
     *
     * @return array
     */
    protected static function formatLogo() {
        $strLogo = <<<queryphp
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
        
        return array_map ( function ($strItem) {
            return addslashes ( $strItem );
        }, explode ( PHP_EOL, $strLogo ) );
    }
}