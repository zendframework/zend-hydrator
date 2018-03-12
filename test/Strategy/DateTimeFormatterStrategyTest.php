<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendTest\Hydrator\Strategy;

use DateTime;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use stdClass;
use Zend\Hydrator\Strategy\DateTimeFormatterStrategy;

/**
 * Tests for {@see DateTimeFormatterStrategy}
 *
 * @covers \Zend\Hydrator\Strategy\DateTimeFormatterStrategy
 */
class DateTimeFormatterStrategyTest extends TestCase
{
    public function testHydrate()
    {
        $strategy = new DateTimeFormatterStrategy('Y-m-d');
        $this->assertEquals('2014-04-26', $strategy->hydrate('2014-04-26')->format('Y-m-d'));

        $strategy = new DateTimeFormatterStrategy('Y-m-d', new \DateTimeZone('Asia/Kathmandu'));

        $date = $strategy->hydrate('2014-04-26');
        $this->assertEquals('Asia/Kathmandu', $date->getTimezone()->getName());
    }

    public function testExtract()
    {
        $strategy = new DateTimeFormatterStrategy('d/m/Y');
        $this->assertEquals('26/04/2014', $strategy->extract(new \DateTime('2014-04-26')));
    }

    public function testGetNullWithInvalidDateOnHydration()
    {
        $strategy = new DateTimeFormatterStrategy('Y-m-d');
        $this->assertEquals(null, $strategy->hydrate(null));
        $this->assertEquals(null, $strategy->hydrate(''));
    }

    public function testCanExtractIfNotDateTime()
    {
        $strategy = new DateTimeFormatterStrategy();
        $date = $strategy->extract(new \stdClass);

        $this->assertInstanceOf(\stdClass::class, $date);
    }

    public function testCanHydrateWithInvalidDateTime()
    {
        $strategy = new DateTimeFormatterStrategy('d/m/Y');
        $this->assertSame('foo bar baz', $strategy->hydrate('foo bar baz'));
    }

    public function testAcceptsStringCastableDateTimeFormat()
    {
        $format = $this->getMockBuilder(stdClass::class)
            ->setMethods(['__toString'])
            ->getMock();

        $format->expects($this->once())->method('__toString')->will($this->returnValue('d/m/Y'));

        $strategy = new DateTimeFormatterStrategy($format);

        $this->assertEquals('26/04/2014', $strategy->extract(new \DateTime('2014-04-26')));
        $this->assertEquals('26/04/2015', $strategy->extract(new \DateTime('2015-04-26')));
    }

    public function testCanExtractAnyDateTimeInterface()
    {
        $dateMock = $this
            ->getMockBuilder(DateTime::class)
            ->getMock();

        $format = 'Y-m-d';
        $dateMock
            ->expects($this->once())
            ->method('format')
            ->with($format);

        $dateImmutableMock = $this
            ->getMockBuilder(DateTimeImmutable::class)
            ->getMock();

        $dateImmutableMock
            ->expects($this->once())
            ->method('format')
            ->with($format);

        $strategy = new DateTimeFormatterStrategy($format);

        $strategy->extract($dateMock);
        $strategy->extract($dateImmutableMock);
    }
}
