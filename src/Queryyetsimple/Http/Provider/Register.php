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
namespace Queryyetsimple\Http\Provider;

use Queryyetsimple\{
    Di\Provider,
    Http\Request,
    Http\Response
};

/**
 * http 服务提供者
 *
 * @author Xiangmin Liu <635750556@qq.com>
 * @package $$
 * @since 2017.08.26
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
        $this->request();
        $this->response();
    }

    /**
     * 可用服务提供者
     *
     * @return array
     */
    public static function providers()
    {
        return [
            'request' => 'Queryyetsimple\Http\Request',
            'response' => 'Queryyetsimple\Http\Response'
        ];
    }

    /**
     * 注册 request 服务
     *
     * @return void
     */
    protected function request()
    {
        $this->singleton('request', function ($project) {
            return new Request($project['session'], $project['cookie'], [
                'var_method' => $project['option']['var_method'],
                'var_ajax' => $project['option']['var_ajax'],
                'var_pjax' => $project['option']['var_pjax']
            ]);
        });
    }

    /**
     * 注册 response 服务
     *
     * @return void
     */
    protected function response()
    {
        $this->singleton('response', function ($project) {
            return new Response($project['router'], $project['view'], $project['session'], $project['cookie'], [
                'action_fail' => $project['option']['view\action_fail'],
                'action_success' => $project['option']['view\action_success'],
                'default_response' => $project ['option'] ['default_response']
            ]);
        });
    }
}