<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace ZendTest\Hydrator\Iterator;

use ArrayObject;
use PHPUnit\Framework\TestCase;
use Zend\Hydrator\ArraySerializable;
use Zend\Hydrator\Exception\InvalidArgumentException;
use Zend\Hydrator\Iterator\HydratingArrayIterator;

/**
 * @covers Zend\Hydrator\Iterator\HydratingArrayIterator<extended>
 */
class HydratingArrayIteratorTest extends TestCase
{
    public function testHydratesObjectAndClonesOnCurrent()
    {
        $data = [
            ['foo' => 'bar'],
            ['baz' => 'bat'],
        ];

        $object   = new ArrayObject();

        $hydratingIterator = new HydratingArrayIterator(new ArraySerializable(), $data, $object);

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

        $hydratingIterator = new HydratingArrayIterator(new ArraySerializable(), $data, '\ArrayObject');

        $hydratingIterator->rewind();
        $this->assertEquals(new ArrayObject($data[0]), $hydratingIterator->current());
    }

    public function testThrowingInvalidArgumentExceptionWhenSettingPrototypeToInvalidClass()
    {
        $this->expectException(InvalidArgumentException::class);
        $hydratingIterator = new HydratingArrayIterator(new ArraySerializable(), [], 'not a real class');
    }
}
