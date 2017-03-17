<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendTest\Hydrator;

use ArrayObject;
use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Zend\Hydrator\DelegatingHydrator;
use Zend\Hydrator\HydratorInterface;

/**
 * Unit tests for {@see DelegatingHydrator}
 *
 * @covers \Zend\Hydrator\DelegatingHydrator
 */
class DelegatingHydratorTest extends TestCase
{
    /**
     * @var DelegatingHydrator
     */
    protected $hydrator;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $hydrators;

    /**
     * @var ArrayObject
     */
    protected $object;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $this->hydrators = $this->prophesize(ContainerInterface::class);
        $this->hydrator = new DelegatingHydrator($this->hydrators->reveal());
        $this->object = new ArrayObject;
    }

    public function testExtract()
    {
        $hydrator = $this->prophesize(HydratorInterface::class);
        $hydrator->extract($this->object)->willReturn(['foo' => 'bar']);

        $this->hydrators->has(Argument::type(ArrayObject::class))->willReturn(true);
        $this->hydrators->get(ArrayObject::class)->willReturn($hydrator->reveal());

        $this->assertEquals(['foo' => 'bar'], $this->hydrator->extract($this->object));
    }

    public function testHydrate()
    {
        $hydrator = $this->prophesize(HydratorInterface::class);
        $hydrator->hydrate(['foo' => 'bar'], $this->object)->willReturn($this->object);

        $this->hydrators->has(Argument::type(ArrayObject::class))->willReturn(true);
        $this->hydrators->get(ArrayObject::class)->willReturn($hydrator->reveal());

        $this->assertEquals($this->object, $this->hydrator->hydrate(['foo' => 'bar'], $this->object));
    }
}
