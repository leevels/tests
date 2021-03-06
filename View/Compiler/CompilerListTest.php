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
 * (c) 2010-2019 http://queryphp.com All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\View\Compiler;

use Tests\TestCase;

/**
 * compiler list test.
 *
 * @author Xiangmin Liu <635750556@qq.com>
 *
 * @since 2018.06.07
 *
 * @version 1.0
 *
 * @api(
 *     title="List 循环",
 *     path="template/list",
 *     description="list 标签也是用于循环输出，解析后的本质为 foreach，同时 foreach 比较符合大家的习惯。",
 * )
 */
class CompilerListTest extends TestCase
{
    use Compiler;

    /**
     * @api(
     *     title="code",
     *     description="",
     *     note="",
     * )
     */
    public function testCode()
    {
        $parser = $this->createParser();

        $source = <<<'eot'
{list $list $key $value}
    {$key} - {$value}
{/list}
eot;

        $compiled = <<<'eot'
<?php if (is_array($list)): foreach($list as $key => $value): ?>
    <?php echo $key; ?> - <?php echo $value; ?>
<?php endforeach; endif; ?>
eot;

        $this->assertSame($compiled, $parser->doCompile($source, null, true));
    }

    /**
     * @api(
     *     title="code 省略键值",
     *     description="有时候我们不需要键值，这个时候我们在模板中写下如下的代码：",
     *     note="",
     * )
     */
    public function testCodeFull()
    {
        $parser = $this->createParser();
        $source = <<<'eot'
{list $list $value}
    {$value}
{/list}
eot;

        $compiled = <<<'eot'
<?php if (is_array($list)): foreach($list as $value): ?>
    <?php echo $value; ?>
<?php endforeach; endif; ?>
eot;

        $this->assertSame($compiled, $parser->doCompile($source, null, true));
    }

    /**
     * @api(
     *     title="node",
     *     description="",
     *     note="",
     * )
     */
    public function testNode()
    {
        $parser = $this->createParser();
        $source = <<<'eot'
<list for=list value=my_value key=my_key index=my_index>
    {$my_index} {$my_key} {$my_value}
</list>
eot;

        $compiled = <<<'eot'
<?php $my_index = 1; ?>
<?php if (is_array($list)): foreach ($list as $my_key => $my_value): ?>
    <?php echo $my_index; ?> <?php echo $my_key; ?> <?php echo $my_value; ?>
<?php $my_index++; ?>
<?php endforeach; endif; ?>
eot;

        $this->assertSame($compiled, $parser->doCompile($source, null, true));
    }

    /**
     * @api(
     *     title="node 省略键值",
     *     description="有时候我们不需要键值，这个时候我们在模板中写下如下的代码：",
     *     note="",
     * )
     */
    public function testNodeFull()
    {
        $parser = $this->createParser();
        $source = <<<'eot'
<list for=list>
    {$index} {$key} {$value}
</list>
eot;

        $compiled = <<<'eot'
<?php $index = 1; ?>
<?php if (is_array($list)): foreach ($list as $key => $value): ?>
    <?php echo $index; ?> <?php echo $key; ?> <?php echo $value; ?>
<?php $index++; ?>
<?php endforeach; endif; ?>
eot;

        $this->assertSame($compiled, $parser->doCompile($source, null, true));
    }
}
