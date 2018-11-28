<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace ZendTest\Hydrator\Aggregate;

use PHPUnit\Framework\TestCase;
use stdClass;
use Zend\Hydrator\Aggregate\HydrateEvent;

/**
 * Unit tests for {@see HydrateEvent}
 */
class HydrateEventTest extends TestCase
{
    /**
     * @covers \Zend\Hydrator\Aggregate\HydrateEvent
     */
    public function testEvent()
    {
        $target    = new stdClass();
        $hydrated1 = new stdClass();
        $data1     = ['president' => 'Zaphod'];
        $event     = new HydrateEvent($target, $hydrated1, $data1);
        $data2     = ['maintainer' => 'Marvin'];
        $hydrated2 = new stdClass();

        $this->assertSame(HydrateEvent::EVENT_HYDRATE, $event->getName());
        $this->assertSame($target, $event->getTarget());
        $this->assertSame($hydrated1, $event->getHydratedObject());
        $this->assertSame($data1, $event->getHydrationData());

        $event->setHydrationData($data2);

        $this->assertSame($data2, $event->getHydrationData());


        $event->setHydratedObject($hydrated2);

        $this->assertSame($hydrated2, $event->getHydratedObject());
    }
}
