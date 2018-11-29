<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace ZendTest\Hydrator\Strategy;

use PHPUnit\Framework\TestCase as TestCase;
use Zend\Hydrator\Exception\InvalidArgumentException;
use Zend\Hydrator\Strategy\SerializableStrategy;
use Zend\Serializer\Adapter\PhpSerialize;
use Zend\Serializer\Serializer;

/**
 * @covers Zend\Hydrator\Strategy\SerializableStrategy<extended>
 */
class SerializableStrategyTest extends TestCase
{
    public function testCannotUseBadArgumentSerializer()
    {
        $this->expectException(InvalidArgumentException::class);
        $serializerStrategy = new SerializableStrategy(false);
    }

    public function testUseBadSerializerObject()
    {
        $serializer = Serializer::factory('phpserialize');
        $serializerStrategy = new SerializableStrategy($serializer);
        $this->assertEquals($serializer, $serializerStrategy->getSerializer());
    }

    public function testUseBadSerializerString()
    {
        $serializerStrategy = new SerializableStrategy('phpserialize');
        $this->assertEquals(PhpSerialize::class, get_class($serializerStrategy->getSerializer()));
    }

    public function testCanSerialize()
    {
        $serializer = Serializer::factory('phpserialize');
        $serializerStrategy = new SerializableStrategy($serializer);
        $serialized = $serializerStrategy->extract('foo');
        $this->assertEquals($serialized, 's:3:"foo";');
    }

    public function testCanUnserialize()
    {
        $serializer = Serializer::factory('phpserialize');
        $serializerStrategy = new SerializableStrategy($serializer);
        $serialized = $serializerStrategy->hydrate('s:3:"foo";');
        $this->assertEquals($serialized, 'foo');
    }
}
