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
namespace Queryyetsimple\Filesystem;

use Queryyetsimple\Support\Option;
use League\Flysystem\Filesystem as LeagueFilesystem;

/**
 * connect 驱动抽象类
 *
 * @author Xiangmin Liu <635750556@qq.com>
 * @package $$
 * @since 2017.08.29
 * @see https://flysystem.thephpleague.com/api/
 * @version 1.0
 */
abstract class Connect
{
    use Option;

    /**
     * Filesystem
     *
     * @var \League\Flysystem\Filesystem
     */
    protected $objFilesystem;

    /**
     * 构造函数
     *
     * @param array $arrOption
     * @return void
     */
    public function __construct(array $arrOption = [])
    {
        $this->options($arrOption);
        $this->filesystem();
    }

    /**
     * 返回 Filesystem
     *
     * @return \League\Flysystem\Filesystem
     */
    public function getFilesystem()
    {
        return $this->objFilesystem;
    }

    /**
     * 生成 Filesystem
     *
     * @return \League\Flysystem\Filesystem
     */
    protected function filesystem()
    {
        return $this->objFilesystem = new LeagueFilesystem($this->makeConnect(), $this->getOptions());
    }

    /**
     * call 
     *
     * @param string $sMethod
     * @param array $arrArgs
     * @return mixed
     */
    public function __call(string $sMethod, array $arrArgs)
    {
        return $this->objFilesystem->$sMethod(...$arrArgs);
    }
}