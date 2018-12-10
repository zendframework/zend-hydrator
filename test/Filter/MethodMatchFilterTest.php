<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace ZendTest\Hydrator\Filter;

use PHPUnit\Framework\TestCase;
use Zend\Hydrator\Filter\MethodMatchFilter;

class MethodMatchFilterTest extends TestCase
{
    public function providerFilter()
    {
        return [
            ['foo', true,],
            ['bar', false,],
            ['class::foo', true,],
            ['class::bar', false,],
        ];
    }

    /**
     * @dataProvider providerFilter
     */
    public function testFilter($methodName, $expected)
    {
        $testedInstance = new MethodMatchFilter('foo', false);
        self::assertEquals($expected, $testedInstance->filter($methodName));

        $testedInstance = new MethodMatchFilter('foo', true);
        self::assertEquals(! $expected, $testedInstance->filter($methodName));
    }
}
