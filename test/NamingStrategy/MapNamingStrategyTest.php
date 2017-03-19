<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendTest\Hydrator\NamingStrategy;

use PHPUnit\Framework\TestCase;
use Zend\Hydrator\NamingStrategy\MapNamingStrategy;

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
        $this->expectException('InvalidArgumentException');
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
