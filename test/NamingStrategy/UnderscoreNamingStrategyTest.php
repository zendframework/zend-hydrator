<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace ZendTest\Hydrator\NamingStrategy;

use PHPUnit\Framework\TestCase;
use Zend\Hydrator\NamingStrategy\UnderscoreNamingStrategy;

/**
 * Unit tests for {@see UnderscoreNamingStrategy}
 *
 * @covers \Zend\Hydrator\NamingStrategy\UnderscoreNamingStrategy
 */
class UnderscoreNamingStrategyTest extends TestCase
{
    public function testNameHydratesToCamelCase()
    {
        $strategy = new UnderscoreNamingStrategy();
        $this->assertEquals('fooBarBaz', $strategy->hydrate('foo_bar_baz'));
    }

    public function testNameExtractsToUnderscore()
    {
        $strategy = new UnderscoreNamingStrategy();
        $this->assertEquals('foo_bar_baz', $strategy->extract('fooBarBaz'));
    }

    /**
     * @group 6422
     * @group 6420
     */
    public function testNameHydratesToStudlyCaps()
    {
        $strategy = new UnderscoreNamingStrategy();

        $this->assertEquals('fooBarBaz', $strategy->hydrate('Foo_Bar_Baz'));
    }
}
