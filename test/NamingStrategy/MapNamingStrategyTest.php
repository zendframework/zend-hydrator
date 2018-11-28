<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace ZendTest\Hydrator\NamingStrategy;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Zend\Hydrator\NamingStrategy\MapNamingStrategy;

/**
 * @covers Zend\Hydrator\NamingStrategy\MapNamingStrategy<extended>
 */
class MapNamingStrategyTest extends TestCase
{
    public function testHydrateMap()
    {
        $namingStrategy = new MapNamingStrategy(['foo' => 'bar']);

        $this->assertEquals('bar', $namingStrategy->hydrate('foo'));
        $this->assertEquals('foo', $namingStrategy->extract('bar'));
    }

    public function testHydrateAndExtractMaps()
    {
        $namingStrategy = new MapNamingStrategy(
            ['foo' => 'foo-hydrated'],
            ['bar' => 'bar-extracted']
        );

        $this->assertEquals('foo-hydrated', $namingStrategy->hydrate('foo'));
        $this->assertEquals('bar-extracted', $namingStrategy->extract('bar'));
    }

    public function testSingleMapInvalidValue()
    {
        $this->expectException(InvalidArgumentException::class);
        new MapNamingStrategy(['foo' => 3.1415]);
    }

    public function testReturnSpecifiedValue()
    {
        $namingStrategy = new MapNamingStrategy(
            [ 'foo' => 'foo-hydrated'],
            [ 'bar' => 'bar-extracted']
        );

        $name = 'foobar';

        $this->assertEquals($name, $namingStrategy->extract($name));
        $this->assertEquals($name, $namingStrategy->hydrate($name));
    }
}
