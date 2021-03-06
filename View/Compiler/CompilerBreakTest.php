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
 * compiler break test.
 *
 * @author Xiangmin Liu <635750556@qq.com>
 *
 * @since 2018.06.07
 *
 * @version 1.0
 *
 * @api(
 *     title="跳出循环",
 *     path="template/break",
 *     description="break 和 continue 是各种循环中非常重要的两个流程标记语言，框架当然也会支持它们。",
 * )
 */
class CompilerBreakTest extends TestCase
{
    use Compiler;

    /**
     * @api(
     *     title="break 标签",
     *     description="",
     *     note="",
     * )
     */
    public function testBaseUse()
    {
        $parser = $this->createParser();

        $source = <<<'eot'
<list for=list>
   <if condition="$value eq 'H'">
       <break/>
   </if>
   {$value}
</list>
eot;

        $compiled = <<<'eot'
<?php $index = 1; ?>
<?php if (is_array($list)): foreach ($list as $key => $value): ?>
   <?php if ($value == 'H'): ?>
       <?php break; ?>
   <?php endif; ?>
   <?php echo $value; ?>
<?php $index++; ?>
<?php endforeach; endif; ?>
eot;

        $this->assertSame($compiled, $parser->doCompile($source, null, true));
    }

    /**
     * @api(
     *     title="ontinue 标签",
     *     description="",
     *     note="",
     * )
     */
    public function testContinue()
    {
        $parser = $this->createParser();

        $source = <<<'eot'
<list for=list>
   <if condition="$value eq 'H'">
       <continue/>
   </if>
   {$value}
</list>
eot;

        $compiled = <<<'eot'
<?php $index = 1; ?>
<?php if (is_array($list)): foreach ($list as $key => $value): ?>
   <?php if ($value == 'H'): ?>
       <?php continue; ?>
   <?php endif; ?>
   <?php echo $value; ?>
<?php $index++; ?>
<?php endforeach; endif; ?>
eot;

        $this->assertSame($compiled, $parser->doCompile($source, null, true));
    }
}
