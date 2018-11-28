<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace ZendTest\Hydrator\NamingStrategy;

use PHPUnit\Framework\TestCase;
use Zend\Hydrator\NamingStrategy\ArrayMapNamingStrategy;

/**
 * Tests for {@see ArrayMapNamingStrategy}
 *
 * @covers \Zend\Hydrator\NamingStrategy\ArrayMapNamingStrategy
 */
class ArrayMapNamingStrategyTest extends TestCase
{
    public function testGetSameNameWithEmptyMap()
    {
        $strategy = new ArrayMapNamingStrategy([]);
        $this->assertEquals('some_stuff', $strategy->hydrate('some_stuff'));
        $this->assertEquals('some_stuff', $strategy->extract('some_stuff'));
    }

    public function testExtract()
    {
        $strategy = new ArrayMapNamingStrategy(['stuff3' => 'stuff4']);
        $this->assertEquals('stuff4', $strategy->extract('stuff3'));
    }

    public function testHydrate()
    {
        $strategy = new ArrayMapNamingStrategy(['foo' => 'bar']);
        $this->assertEquals('foo', $strategy->hydrate('bar'));
    }
}
