<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace ZendTest\Hydrator\NamingStrategy;

use PHPUnit\Framework\TestCase;
use Zend\Hydrator\NamingStrategy\IdentityNamingStrategy;

/**
 * Tests for {@see IdentityNamingStrategy}
 *
 * @covers \Zend\Hydrator\NamingStrategy\IdentityNamingStrategy
 */
class IdentityNamingStrategyTest extends TestCase
{
    /**
     * @dataProvider getTestedNames
     *
     * @param string $name
     */
    public function testHydrate($name)
    {
        $namingStrategy = new IdentityNamingStrategy();

        $this->assertSame($name, $namingStrategy->hydrate($name));
    }

    /**
     * @dataProvider getTestedNames
     *
     * @param string $name
     */
    public function testExtract($name)
    {
        $namingStrategy = new IdentityNamingStrategy();

        $this->assertSame($name, $namingStrategy->extract($name));
    }

    /**
     * Data provider
     *
     * @return string[][]
     */
    public function getTestedNames()
    {
        return [
            'foo' => ['foo'],
            'bar' => ['bar'],
        ];
    }
}
