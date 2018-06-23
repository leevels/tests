<?php

declare(strict_types=1);

/*
 * This file is part of the ************************ package.
 * _____________                           _______________
 *  ______/     \__  _____  ____  ______  / /_  _________
 *   ____/ __   / / / / _ \/ __`\/ / __ \/ __ \/ __ \___
 *    __/ / /  / /_/ /  __/ /  \  / /_/ / / / / /_/ /__
 *      \_\ \_/\____/\___/_/   / / .___/_/ /_/ .___/
 *         \_\                /_/_/         /_/
 *
 * The PHP Framework For Code Poem As Free As Wind. <Query Yet Simple>
 * (c) 2010-2018 http://queryphp.com All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests;

use PHPUnit\Framework\TestCase as TestCases;

/**
 * phpunit 测试用例.
 *
 * @author Xiangmin Liu <635750556@qq.com>
 *
 * @since 2017.05.09
 *
 * @version 1.0
 */
abstract class TestCase extends TestCases
{
    /**
     * setUpBeforeClass.
     */
    public static function setUpBeforeClass()
    {
    }

    /**
     * tearDownAfterClass.
     */
    public static function tearDownAfterClass()
    {
    }

    /**
     * setUp.
     */
    protected function setUp()
    {
    }

    /**
     * tearDown.
     */
    protected function tearDown()
    {
    }

    protected function varExport(array $data)
    {
        file_put_contents(
            __DIR__.'/logs/'.static::class.'.log',
            var_export($data, true)
        );

        return var_export($data, true);
    }
}
