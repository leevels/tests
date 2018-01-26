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
namespace Queryyetsimple\I18n\Provider;

use Queryyetsimple\{
    I18n\I18n,
    I18n\Load,
    Di\Provider
};

/**
 * i18n 服务提供者
 *
 * @author Xiangmin Liu <635750556@qq.com>
 * @package $$
 * @since 2017.05.12
 * @version 1.0
 */
class Register extends Provider
{

    /**
     * 注册服务
     *
     * @return void
     */
    public function register()
    {
        $this->i18n();
        $this->i18nLoad();
    }

    /**
     * bootstrap
     *
     * @return void
     */
    public function bootstrap()
    {
        $this->console();
    }

    /**
     * 可用服务提供者
     *
     * @return array
     */
    public static function providers()
    {
        return [
            'i18n' => [
                'queryyetsimple\I18n\I18n',
                'Queryyetsimple\I18n\II18n',
                'Qys\I18n\I18n',
                'Qys\I18n\II18n'     
            ],
            'load' => [
                'Queryyetsimple\I18n\Load',
                'Qys\I18n\Load'
            ]
        ];
    }

    /**
     * 注册 i18n 服务
     *
     * @return void
     */
    protected function i18n()
    {
        $this->singleton('i18n', function ($project) {
            return new I18n($project['option']['i18n\default']);
        });
    }

    /**
     * 注册 i18n.load 服务
     *
     * @return void
     */
    protected function i18nLoad()
    {
        $this->singleton('i18n.load', function () {
            return new Load();
        });
    }

    /**
     * 载入命令包
     *
     * @return void
     */
    protected function console()
    {
        $this->loadCommandNamespace('Queryyetsimple\I18n\Console');
    }
}
