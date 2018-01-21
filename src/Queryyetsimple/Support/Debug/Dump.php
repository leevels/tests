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
namespace Queryyetsimple\Support\Debug;

use Symfony\Component\VarDumper\{
    Dumper\CliDumper,
    Cloner\VarCloner,
    Dumper\HtmlDumper
};

/**
 * 调试一个变量
 *
 * @author Xiangmin Liu <635750556@qq.com>
 * @package $$
 * @since 2017.04.05
 * @version 1.0
 */
class Dump
{

    /**
     * 调试一个变量
     *
     * @param mixed $var
     * @param boolean $simple
     * @return void|string
     */
    public static function dump($var, $simple = false)
    {
        static $dump, $varCloner;

        if ($simple === false && class_exists(CliDumper::class)) {
            if (! $dump) {
                $dump = ('cli' === PHP_SAPI ? new CliDumper() : new HtmlDumper());
                $varCloner = new VarCloner();
            }

            $dump->dump($varCloner->cloneVar($var));
        } else {
            $args = func_get_args();
            array_shift($args);
            array_shift($args);
            array_unshift($args, $var);

            return call_user_func_array([
                'Queryyetsimple\Support\Debug\Dump',
                'varDump'
            ], $args);
        }
    }

    /**
     * 调试变量
     *
     * @param mixed $var
     * @param boolean $echo
     * @return mixed
     */
    public static function varDump($var, $echo = true)
    {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();

        if (! extension_loaded('xdebug')) {
            $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
            $output = 'cli' === PHP_SAPI ? $output : '<pre>' . htmlspecialchars($output, ENT_COMPAT, 'UTF-8') . '</pre>';
        }

        if ($echo) {
            echo $output;
        } else {
            return $output;
        }
    }
}