<?php
// [$QueryPHP] The PHP Framework For Code Poem As Free As Wind. <Query Yet Simple>
// ©2010-2017 http://queryphp.com All rights reserved.
namespace tests;

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

use queryyetsimple\psr4;
use queryyetsimple\router;

/**
 * phpunit 内部启动文件
 *
 * @author Xiangmin Liu <635750556@qq.com>
 * @package $$
 * @since 2017.05.09
 * @version 1.0
 */
psr4::import('tests', dirname(env('app_bootstrap')));
router::bind('phpunittests://bootstrap/index', function () {
    return (new application())->run();
});
