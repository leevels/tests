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
namespace Queryyetsimple\Support;

/**
 * 数组辅助函数
 *
 * @author Xiangmin Liu <635750556@qq.com>
 * @package $$
 * @since 2017.05.05
 * @version 1.0
 */
class Arr
{

    /**
     * 数组数据格式化
     *
     * @param mixed $mixInput
     * @param string $sDelimiter
     * @param boolean $bAllowedEmpty
     * @return mixed
     */
    public static function normalize($mixInput, $sDelimiter = ',', $bAllowedEmpty = false)
    {
        if (is_array($mixInput) || is_string($mixInput)) {
            if (! is_array($mixInput)) {
                $mixInput = explode($sDelimiter, $mixInput);
            }

            $mixInput = array_filter($mixInput); // 过滤null
            if ($bAllowedEmpty === true) {
                return $mixInput;
            } else {
                $mixInput = array_map('trim', $mixInput);
                return array_filter($mixInput, 'strlen');
            }
        } else {
            return $mixInput;
        }
    }

    /**
     * 数组合并支持 + 算法
     *
     * @param array $arrOption
     * @param boolean $booRecursion
     * @return array
     */
    public static function merge($arrOption, $booRecursion = true)
    {
        $arrExtend = [];

        foreach ($arrOption as $strKey => $mixTemp) {
            if (strpos($strKey, '+') === 0) {
                $arrExtend[ltrim($strKey, '+')] = $mixTemp;
                unset($arrOption[$strKey]);
            }
        }

        foreach ($arrExtend as $strKey => $mixTemp) {
            if (isset($arrOption[$strKey]) && is_array($arrOption[$strKey]) && is_array($mixTemp)) {
                $arrOption[$strKey] = array_merge($arrOption[$strKey], $mixTemp);
            } else {
                $arrOption[$strKey] = $mixTemp;
            }
        }

        if ($booRecursion === true) {
            foreach ($arrOption as $strKey => $mixTemp) {
                if (is_array($mixTemp)) {
                    $arrOption[$strKey] = static::merge($mixTemp);
                }
            }
        }

        return $arrOption;
    }
}
