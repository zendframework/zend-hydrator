<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace ZendTest\Hydrator;

use PHPUnit\Framework\TestCase;

/**
 * @requires PHP 5.4
 * @covers Zend\Hydrator\HydratorAwareTrait<extended>
 */
class HydratorAwareTraitTest extends TestCase
{
    public function testSetHydrator()
    {
        $object = $this->getObjectForTrait('\Zend\Hydrator\HydratorAwareTrait');

        $this->assertSame(null, $object->getHydrator());

        $hydrator = $this->getMockForAbstractClass('\Zend\Hydrator\AbstractHydrator');

        $object->setHydrator($hydrator);

        $this->assertSame($hydrator, $object->getHydrator());
    }

    public function testGetHydrator()
    {
        $object = $this->getObjectForTrait('\Zend\Hydrator\HydratorAwareTrait');

        $this->assertNull($object->getHydrator());

        $hydrator = $this->getMockForAbstractClass('\Zend\Hydrator\AbstractHydrator');

        $object->setHydrator($hydrator);

        $this->assertEquals($hydrator, $object->getHydrator());
    }
}
