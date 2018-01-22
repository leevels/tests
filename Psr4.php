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

use RuntimeException;
use Composer\Autoload\ClassLoader;

/**
 * psr4 自动载入规范
 *
 * @author Xiangmin Liu <635750556@qq.com>
 * @package $$
 * @since 2016.11.17
 * @version 1.0
 */
class Psr4 implements IPsr4
{

    /**
     * Composer
     *
     * @var \Composer\Autoload\ClassLoader
     */
    protected $composer;

    /**
     * 沙盒路径
     *
     * @var string
     */
    protected $sandbox;

    /**
     * 系统命名空间
     *
     * @var string
     */
    protected $namespace;

    /**
     * 短命名空间
     *
     * @var string
     */
    protected $shortNamespace;

    /**
     * 框架自定义命名空间
     *
     * @var string
     */
    const DEFAULT_NAMESPACE = 'queryyetsimple';

    /**
     * 设置 composer
     *
     * @param \Composer\Autoload\ClassLoader $composer
     * @param string $sandbox
     * @param string $namespace
     * @param string $shortNamespace
     * @return void
     */
    public function __construct(ClassLoader $composer, $sandbox, $namespace, $shortNamespace)
    {
        $this->composer = $composer;
        $this->sandbox = $sandbox;
        $this->namespace = $namespace;
        $this->shortNamespace = $shortNamespace;
    }

    /**
     * 获取 composer
     *
     * @return \Composer\Autoload\ClassLoader
     */
    public function composer()
    {
        return $this->composer;
    }

    /**
     * 导入一个目录中命名空间结构
     *
     * @param string $sNamespace 命名空间名字
     * @param string $sPackage 命名空间路径
     * @param boolean $force 强制覆盖
     * @return void
     */
    public function import($sNamespace, $sPackage, $force = false)
    {
        if (! is_dir($sPackage)) {
            return;
        }

        if ($force === false && ! is_null($this->namespaces($sNamespace))) {
            return;
        }

        $sPackagePath = realpath($sPackage);
        $this->composer()->setPsr4($sNamespace . '\\', $sPackagePath);
    }

    /**
     * 获取命名空间路径
     *
     * @param string $sNamespace
     * @return string|null
     */
    public function namespaces($sNamespace)
    {
        $arrNamespace = explode('\\', $sNamespace);
        $arrPrefix = $this->composer()->getPrefixesPsr4();

        if (! isset($arrPrefix[$arrNamespace[0] . '\\'])) {
            return null;
        }

        $arrNamespace[0] = $arrPrefix[$arrNamespace[0] . '\\'][0];
        return implode('/', $arrNamespace);
    }

    /**
     * 根据命名空间取得文件路径
     *
     * @param string $strFile
     * @return string
     */
    public function file($strFile)
    {
        if (($strNamespace = $this->namespaces($strFile))) {
            return $strNamespace . '.php';
        } else {
            return $strFile . '.php';
        }
    }

    /**
     * 框架自动载入
     *
     * @param string $strClass
     * @return void
     */
    public function autoload($strClass)
    {
        if (strpos($strClass, '\\') === false) {
            return;
        }

        foreach ([
            $this->namespace,
            $this->shortNamespace
        ] as $strNamespace) {
            if (strpos($strClass, $strNamespace . '\\') === 0 && is_file(($strSandbox = $this->sandbox . '/' . str_replace('\\', '/', $strClass) . '.php'))) {
                require_once $strSandbox;
                return;
            }
        }

        if (strpos($strClass, $this->shortNamespace . '\\') !== false) {
            $this->shortNamespaceMap($strClass);
        }
    }

    /**
     * 框架命名空间自动关联
     *
     * @param string $strClass
     * @return void
     */
    protected function shortNamespaceMap($strClass)
    {
        $strTryMapClass = str_replace($this->shortNamespace . '\\', $this->namespace . '\\', $strClass);
        
        if (class_exists($strTryMapClass) || interface_exists($strTryMapClass)) {
            $arrClass = explode('\\', $strClass);
            $strDefinedClass = array_pop($arrClass);
            $strNamespace = implode('\\', $arrClass);

            $strSandboxContent = sprintf('namespace %s; %s %s extends  \%s {}', $strNamespace, class_exists($strTryMapClass) ? 'class' : 'interface', $strDefinedClass, $strTryMapClass);

            eval($strSandboxContent);
        }
    }
}
