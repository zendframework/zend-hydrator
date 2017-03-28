<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendTest\Hydrator\Aggregate;

use PHPUnit\Framework\TestCase;
use stdClass;
use Zend\Hydrator\Aggregate\ExtractEvent;
use Zend\Hydrator\Aggregate\HydrateEvent;
use Zend\Hydrator\Aggregate\HydratorListener;

/**
 * Unit tests for {@see HydratorListener}
 */
class HydratorListenerTest extends TestCase
{
    /**
     * @var \Zend\Hydrator\HydratorInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $hydrator;

    /**
     * @var HydratorListener
     */
    protected $listener;

    /**
     * {@inheritDoc}
     *
     * @covers \Zend\Hydrator\Aggregate\HydratorListener::__construct
     */
    public function setUp()
    {
        $this->hydrator = $this->createMock('Zend\Hydrator\HydratorInterface');
        $this->listener = new HydratorListener($this->hydrator);
    }

    /**
     * @covers \Zend\Hydrator\Aggregate\HydratorListener::attach
     */
    public function testAttach()
    {
        $eventManager = $this->createMock('Zend\EventManager\EventManagerInterface');

        $eventManager
            ->expects($this->exactly(2))
            ->method('attach')
            ->with(
                $this->logicalOr(HydrateEvent::EVENT_HYDRATE, ExtractEvent::EVENT_EXTRACT),
                $this->logicalAnd(
                    $this->callback('is_callable'),
                    $this->logicalOr([$this->listener, 'onHydrate'], [$this->listener, 'onExtract'])
                )
            );

        $this->listener->attach($eventManager);
    }

    /**
     * @covers \Zend\Hydrator\Aggregate\HydratorListener::onHydrate
     */
    public function testOnHydrate()
    {
        $object   = new stdClass();
        $hydrated = new stdClass();
        $data     = ['foo' => 'bar'];
        $event    = $this
            ->getMockBuilder('Zend\Hydrator\Aggregate\HydrateEvent')
            ->disableOriginalConstructor()
            ->getMock();

        $event->expects($this->any())->method('getHydratedObject')->will($this->returnValue($object));
        $event->expects($this->any())->method('getHydrationData')->will($this->returnValue($data));

        $this
            ->hydrator
            ->expects($this->once())
            ->method('hydrate')
            ->with($data, $object)
            ->will($this->returnValue($hydrated));
        $event->expects($this->once())->method('setHydratedObject')->with($hydrated);

        $this->assertSame($hydrated, $this->listener->onHydrate($event));
    }

    /**
     * @covers \Zend\Hydrator\Aggregate\HydratorListener::onExtract
     */
    public function testOnExtract()
    {
        $object = new stdClass();
        $data   = ['foo' => 'bar'];
        $event  = $this
            ->getMockBuilder('Zend\Hydrator\Aggregate\ExtractEvent')
            ->disableOriginalConstructor()
            ->getMock();


        $event->expects($this->any())->method('getExtractionObject')->will($this->returnValue($object));

        $this
            ->hydrator
            ->expects($this->once())
            ->method('extract')
            ->with($object)
            ->will($this->returnValue($data));
        $event->expects($this->once())->method('mergeExtractedData')->with($data);

        $this->assertSame($data, $this->listener->onExtract($event));
    }
}
