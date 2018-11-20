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

use Leevel\Database\Ddd\Meta;
use Leevel\Database\Manager;
use Leevel\Database\Mysql;
use Leevel\Di\Container;
use Leevel\Di\IContainer;
use Leevel\Option\Option;
use PDO;

/**
 * 数据辅助方法.
 *
 * @author Xiangmin Liu <635750556@qq.com>
 *
 * @since 2018.06.10
 *
 * @version 1.0
 */
trait Database
{
    protected $databaseConnects;

    protected function createDatabaseConnectBase(array $option = []): Mysql
    {
        $connect = new Mysql($option);

        $this->databaseConnects[] = $connect;

        return $connect;
    }

    protected function createDatabaseConnect()
    {
        return $this->createDatabaseConnectBase([
            'driver'             => 'mysql',
            'separate'           => false,
            'distributed'        => false,
            'master'             => [
                'host'     => $GLOBALS['LEEVEL_ENV']['DATABASE']['MYSQL']['HOST'],
                'port'     => $GLOBALS['LEEVEL_ENV']['DATABASE']['MYSQL']['PORT'],
                'name'     => $GLOBALS['LEEVEL_ENV']['DATABASE']['MYSQL']['NAME'],
                'user'     => $GLOBALS['LEEVEL_ENV']['DATABASE']['MYSQL']['USER'],
                'password' => $GLOBALS['LEEVEL_ENV']['DATABASE']['MYSQL']['PASSWORD'],
                'charset'  => 'utf8',
                'options'  => [
                    PDO::ATTR_PERSISTENT => false,
                ],
            ],
            'slave' => [],
        ]);
    }

    protected function truncateDatabase($tables)
    {
        if ($this->databaseConnects[0]) {
            $connect = $this->databaseConnects[0];
        } else {
            $connect = $this->createDatabaseConnect();
        }

        foreach ($tables as $table) {
            $connect->

            table($table)->

            truncate();
        }
    }

    protected function metaWithoutDatabase()
    {
        Meta::setDatabaseResolver(null);
    }

    protected function metaWithDatabase()
    {
        Meta::setDatabaseResolver(function () {
            $container = new Container();

            $manager = new Manager($container);

            $this->assertInstanceof(IContainer::class, $manager->container());
            $this->assertInstanceof(Container::class, $manager->container());

            $option = new Option([
                'database' => [
                    'default' => 'mysql',
                    'connect' => [
                        'mysql' => [
                            'driver'   => 'mysql',
                            'host'     => $GLOBALS['LEEVEL_ENV']['DATABASE']['MYSQL']['HOST'],
                            'port'     => $GLOBALS['LEEVEL_ENV']['DATABASE']['MYSQL']['PORT'],
                            'name'     => $GLOBALS['LEEVEL_ENV']['DATABASE']['MYSQL']['NAME'],
                            'user'     => $GLOBALS['LEEVEL_ENV']['DATABASE']['MYSQL']['USER'],
                            'password' => $GLOBALS['LEEVEL_ENV']['DATABASE']['MYSQL']['PASSWORD'],
                            'charset'  => 'utf8',
                            'options'  => [
                                PDO::ATTR_PERSISTENT => false,
                            ],
                            'separate'           => false,
                            'distributed'        => false,
                            'master'             => [],
                            'slave'              => [],
                        ],
                    ],
                ],
            ]);

            $container->singleton('option', $option);

            return $manager;
        });
    }

    protected function freeDatabaseConnects()
    {
        // 释放数据库连接，否则会出现 Mysql 连接数过多
        // PDOException: PDO::__construct(): MySQL server has gone away
        foreach ($this->databaseConnects as $k => $connect) {
            unset($this->databaseConnects[$k]);
            $connect->__destruct();
        }
    }
}
