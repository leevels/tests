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
namespace queryyetsimple\pipeline;

use InvalidArgumentException;
use queryyetsimple\support\icontainer;

/**
 * 管道实现类
 *
 * @author Xiangmin Liu <635750556@qq.com>
 * @package $$
 * @since 2017.05.25
 * @version 1.0
 */
class pipeline implements ipipeline
{

    /**
     * 容器
     *
     * @var \queryyetsimple\support\icontainer
     */
    protected $objContainer;

    /**
     * 管道传递的对象
     *
     * @var mixed
     */
    protected $mixPassed;

    /**
     * 管道传递的附加对象
     *
     * @var array
     */
    protected $arrPassedExtend = [];

    /**
     * 管道中所有执行工序
     *
     * @var array
     */
    protected $arrStage = [];

    /**
     * 创建一个管道
     *
     * @param \queryyetsimple\support\icontainer $objContainer
     * @return void
     */
    public function __construct(icontainer $objContainer)
    {
        $this->objContainer = $objContainer;
    }

    /**
     * 将传输对象传入管道
     *
     * @param mixed $mixPassed
     * @return $this
     */
    public function send($mixPassed)
    {
        $this->mixPassed = $mixPassed;
        return $this;
    }

    /**
     * 将附加传输对象传入管道
     *
     * @param mixed $mixPassed
     * @return $this
     */
    public function sendExtend($mixPassed)
    {
        $mixPassed = is_array($mixPassed) ? $mixPassed : func_get_args();
        foreach ($mixPassed as $mixItem) {
            $this->arrPassedExtend[] = $mixItem;
        }
        return $this;
    }

    /**
     * 设置管道中的执行工序
     *
     * @param dynamic|array $mixStages
     * @return $this
     */
    public function through($mixStages)
    {
        $mixStages = is_array($mixStages) ? $mixStages : func_get_args();

        foreach ($mixStages as $mixStage) {
            $this->stage($mixStage);
        }
        return $this;
    }

    /**
     * 添加一道工序
     *
     * @param mixed $mixStage
     * @return $this
     */
    public function stage($mixStage)
    {
        $this->arrStage[] = $mixStage;
        return $this;
    }

    /**
     * 执行管道工序响应结果
     *
     * @param callable $calEnd
     * @return mixed
     */
    public function then(callable $calEnd)
    {
        $calEnd = function ($mixPassed) use ($calEnd) {
            return call_user_func($calEnd, $mixPassed);
        };
        $arrStage = array_reverse($this->arrStage);
        $arrPassedExtend = $this->arrPassedExtend;
        array_unshift($arrPassedExtend, $this->mixPassed);

        return call_user_func_array(array_reduce($arrStage, $this->stageCallback(), $calEnd), $arrPassedExtend);
    }

    /**
     * 工序回调
     *
     * @return \Closure
     */
    protected function stageCallback()
    {
        return function ($calResult, $mixStage) {
            return function ($mixPassed) use ($calResult, $mixStage) {
                $arrArgs = func_get_args();
                array_unshift($arrArgs, $calResult);

                if (is_callable($mixStage)) {
                    return call_user_func_array($mixStage, $arrArgs);
                } else {
                    list($strStage, $arrParams) = $this->parse($mixStage);

                    if (strpos($strStage, '@') !== false) {
                        list($strStage, $strMethod) = explode('@', $strStage);
                    } else {
                        $strMethod = 'handle';
                    }

                    if (($objStage = $this->objContainer->make($strStage)) === false) {
                        throw new InvalidArgumentException(sprintf('Stage %s is not valid.', $strStage));
                    }

                    return call_user_func_array([
                        $objStage,
                        $strMethod
                    ], array_merge($arrArgs, $arrParams));
                }
            };
        };
    }

    /**
     * 解析工序
     *
     * @param string $strStage
     * @return array
     */
    protected function parse($strStage)
    {
        list($strName, $arrArgs) = array_pad(explode(':', $strStage, 2), 2, []);
        if (is_string($arrArgs)) {
            $arrArgs = explode(',', $arrArgs);
        }

        return [
            $strName,
                $arrArgs
        ];
    }
}
