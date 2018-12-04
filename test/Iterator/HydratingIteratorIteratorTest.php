<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace ZendTest\Hydrator\Iterator;

use ArrayIterator;
use ArrayObject;
use PHPUnit\Framework\TestCase;
use Zend\Hydrator\ArraySerializableHydrator;
use Zend\Hydrator\Exception\InvalidArgumentException;
use Zend\Hydrator\Iterator\HydratingIteratorIterator;

/**
 * @covers Zend\Hydrator\Iterator\HydratingIteratorIterator<extended>
 */
class HydratingIteratorIteratorTest extends TestCase
{
    public function testHydratesObjectAndClonesOnCurrent()
    {
        $data = [
            ['foo' => 'bar'],
            ['baz' => 'bat'],
        ];

        $iterator = new ArrayIterator($data);
        $object   = new ArrayObject();

        $hydratingIterator = new HydratingIteratorIterator(new ArraySerializableHydrator(), $iterator, $object);

        $hydratingIterator->rewind();
        $this->assertEquals(new ArrayObject($data[0]), $hydratingIterator->current());
        $this->assertNotSame(
            $object,
            $hydratingIterator->current(),
            'Hydrating Iterator did not clone the object'
        );

        $hydratingIterator->next();
        $this->assertEquals(new ArrayObject($data[1]), $hydratingIterator->current());
    }

    public function testUsingStringForObjectName()
    {
        $data = [
            ['foo' => 'bar'],
        ];

        $iterator = new ArrayIterator($data);

        $hydratingIterator = new HydratingIteratorIterator(new ArraySerializableHydrator(), $iterator, '\ArrayObject');

        $hydratingIterator->rewind();
        $this->assertEquals(new ArrayObject($data[0]), $hydratingIterator->current());
    }

    public function testThrowingInvalidArgumentExceptionWhenSettingPrototypeToInvalidClass()
    {
        $this->expectException(InvalidArgumentException::class);
        $hydratingIterator = new HydratingIteratorIterator(
            new ArraySerializableHydrator(),
            new ArrayIterator(),
            'not a real class'
        );
    }
}
