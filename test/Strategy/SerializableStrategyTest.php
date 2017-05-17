<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendTest\Hydrator\Strategy;

use PHPUnit\Framework\TestCase as TestCase;
use Zend\Hydrator\Exception\InvalidArgumentException;
use Zend\Hydrator\Strategy\SerializableStrategy;
use Zend\Serializer\Adapter\PhpSerialize;
use Zend\Serializer\Serializer;

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
