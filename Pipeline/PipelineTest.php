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
namespace Tests\Pipeline;

use Closure;
use Tests\TestCase;
use Queryyetsimple\Di\Container;
use Queryyetsimple\Pipeline\Pipeline;

/**
 * pipeline 组件测试
 *
 * @author Xiangmin Liu <635750556@qq.com>
 * @package $$
 * @since 2017.05.27
 * @version 1.0
 */
class PipelineTest extends TestCase
{

    public function testPipelineBasic()
    {
        $result = (new Pipeline(new Container))->

        send('hello world')->

        through(['Tests\Pipeline\First', 'Tests\Pipeline\Second'])->

        then();

        $this->assertEquals('i am in first handle and get the send:hello world', $_SERVER['test.first']);
        $this->assertEquals('i am in second handle and get the send:hello world', $_SERVER['test.second']);

        unset($_SERVER['test.first']);
        unset($_SERVER['test.second']);
    }

    public function testPipelineWithThen()
    {
        $thenCallback = function(Closure $next, $send) {
            $_SERVER['test.then'] = 'i am end and get the send:' . $send;
        };

        $result = (new Pipeline(new Container))->

        send('foo bar')->

        through(['Tests\Pipeline\First', 'Tests\Pipeline\Second'])->

        then($thenCallback);

        $this->assertEquals('i am in first handle and get the send:foo bar', $_SERVER['test.first']);
        $this->assertEquals('i am in second handle and get the send:foo bar', $_SERVER['test.second']);
        $this->assertEquals('i am end and get the send:foo bar', $_SERVER['test.then']);

        unset($_SERVER['test.first']);
        unset($_SERVER['test.second']);
        unset($_SERVER['test.then']);
    }

    public function testPipelineWithReturn()
    {
        $pipe1 = function(Closure $next, $send) {
            $result = $next($send);

            $this->assertEquals($result, 'return 2');

            $_SERVER['test.1'] = '1 and get the send:' . $send;

            return 'return 1';
        };

        $pipe2 = function(Closure $next, $send) {
            $result = $next($send);

            $this->assertNull($result);

            $_SERVER['test.2'] = '2 and get the send:' . $send;

            return 'return 2';
        };

        $result = (new Pipeline(new Container))->

        send('return test')->

        through($pipe1, $pipe2)->

        then();

        $this->assertEquals('1 and get the send:return test', $_SERVER['test.1']);
        $this->assertEquals('2 and get the send:return test', $_SERVER['test.2']);

        unset($_SERVER['test.1']);
        unset($_SERVER['test.2']);
    }

    public function testPipelineWithDiConstruct()
    {
        $result = (new Pipeline(new Container))->

        send('hello world')->

        through(['Tests\Pipeline\DiConstruct'])->

        then();

        $this->assertEquals('get class:Tests\Pipeline\TestClass', $_SERVER['test.DiConstruct']);

        unset($_SERVER['test.DiConstruct']);
    }

    public function testPipelineWithSendNoneParams()
    {
        $pipe = function(Closure $next) {
            $this->assertEquals(1, count(func_get_args()));
        };

        $result = (new Pipeline(new Container))->

        through($pipe)->

        then();
    }

    public function testPipelineWithSendMoreParams()
    {
        $pipe = function(Closure $next, $send1, $send2, $send3, $send4) {
            $this->assertEquals($send1, 'hello world');
            $this->assertEquals($send2, 'foo');
            $this->assertEquals($send3, 'bar');
            $this->assertEquals($send4, 'wow');
        };

        $result = (new Pipeline(new Container))->

        send('hello world')->

        send(['foo', 'bar', 'wow'])->

        through($pipe)->

        then();
    }

    public function testPipelineWithThroughMore()
    {
        $_SERVER['test.Through.count'] = 0;

        $pipe = function(Closure $next) {
            $_SERVER['test.Through.count']++;

            $next();
        };

        $result = (new Pipeline(new Container))->

        through($pipe)->

        through($pipe, $pipe, $pipe)->

        through([$pipe, $pipe])->

        then();

        $this->assertEquals(6, $_SERVER['test.Through.count']);

        unset($_SERVER['test.Through.count']);
    }    

    public function testPipelineWithPipeArgs()
    {
        $parameters = ['one', 'two'];

        $result = (new Pipeline(new Container))->

        through('Tests\Pipeline\WithArgs:' . implode(',', $parameters))->

        then();

        $this->assertEquals($parameters, $_SERVER['test.WithArgs']);

        unset($_SERVER['test.WithArgs']);
    }
}

class First
{
    public function handle(Closure $next, $send)
    {
        $_SERVER['test.first'] = 'i am in first handle and get the send:' . $send;

        $next($send);
    }
}

class Second
{
    public function handle(Closure $next, $send)
    {
        $_SERVER['test.second'] = 'i am in second handle and get the send:' . $send;

        $next($send);
    }
}

class WithArgs
{

    protected $args = [];

    public function __construct($one = null, $two = null)
    {
        $this->args = [$one, $two];
    }

    public function handle(Closure $next)
    {
        $_SERVER['test.WithArgs'] = $this->args;

        $next();
    }
}

class TestClass {

}

class DiConstruct
{
    protected $testClass;

    public function __construct(TestClass $testClass)
    {
        $this->testClass = $testClass;
    }

    public function handle(Closure $next, $send)
    {
        $_SERVER['test.DiConstruct'] = 'get class:' . get_class($this->testClass);

        $next($send);
    }
}
