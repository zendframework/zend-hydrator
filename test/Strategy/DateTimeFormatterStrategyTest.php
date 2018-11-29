<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace ZendTest\Hydrator\Strategy;

use DateTime;
use DateTimeImmutable;
use DateTimezone;
use PHPUnit\Framework\TestCase;
use stdClass;
use Zend\Hydrator\Strategy\Exception\InvalidArgumentException;
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

        $strategy = new DateTimeFormatterStrategy('Y-m-d', new DateTimeZone('Asia/Kathmandu'));

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

    /**
     * @dataProvider formatsWithSpecialCharactersProvider
     * @param string $format
     * @param string $expectedValue
     */
    public function testAcceptsCreateFromFormatSpecialCharacters($format, $expectedValue)
    {
        $strategy = new DateTimeFormatterStrategy($format);
        $hydrated = $strategy->hydrate($expectedValue);

        $this->assertInstanceOf(DateTime::class, $hydrated);
        $this->assertEquals($expectedValue, $hydrated->format('Y-m-d'));
    }

    /**
     * @dataProvider formatsWithSpecialCharactersProvider
     * @param string $format
     * @param string $expectedValue
     */
    public function testCanExtractWithCreateFromFormatSpecialCharacters($format, $expectedValue)
    {
        $date      = DateTime::createFromFormat($format, $expectedValue);
        $strategy  = new DateTimeFormatterStrategy($format);
        $extracted = $strategy->extract($date);

        $this->assertEquals($expectedValue, $extracted);
    }

    public function testCanExtractWithCreateFromFormatEscapedSpecialCharacters()
    {
        $date      = DateTime::createFromFormat('Y-m-d', '2018-02-05');
        $strategy  = new DateTimeFormatterStrategy('Y-m-d\\+');
        $extracted = $strategy->extract($date);
        $this->assertEquals('2018-02-05+', $extracted);
    }

    public function formatsWithSpecialCharactersProvider()
    {
        return [
            '!-prepended' => ['!Y-m-d', '2018-02-05'],
            '|-appended'  => ['Y-m-d|', '2018-02-05'],
            '+-appended'  => ['Y-m-d+', '2018-02-05'],
        ];
    }

    public function testCanHydrateWithDateTimeFallback()
    {
        $strategy = new DateTimeFormatterStrategy('Y-m-d', null, true);
        $date = $strategy->hydrate('2018-09-06T12:10:30');

        $this->assertSame('2018-09-06', $date->format('Y-m-d'));

        $strategy = new DateTimeFormatterStrategy('Y-m-d', new DateTimeZone('Europe/Prague'), true);
        $date = $strategy->hydrate('2018-09-06T12:10:30');

        $this->assertSame('Europe/Prague', $date->getTimezone()->getName());
    }

    public function invalidValuesForHydration() : iterable
    {
        return [
            'zero'       => [0],
            'int'        => [1],
            'zero-float' => [0.0],
            'float'      => [1.1],
            'array'      => [['2018-11-20']],
            'object'     => [(object) ['date' => '2018-11-20']],
        ];
    }

    /**
     * @dataProvider invalidValuesForHydration
     * @param mixed $value
     */
    public function testHydrateRaisesExceptionIfValueIsInvalid($value)
    {
        $strategy = new DateTimeFormatterStrategy('Y-m-d');

        $this->expectException(InvalidArgumentException::class);

        $strategy->hydrate($value);
    }

    public function validUnhydratableValues() : iterable
    {
        return [
            'empty string' => [''],
            'null'         => [null],
            'date-time'    => [new DateTimeImmutable('now')],
        ];
    }

    /**
     * @dataProvider validUnhydratableValues
     * @param mixed $value
     */
    public function testReturnsValueVerbatimUnderSpecificConditions($value)
    {
        $strategy = new DateTimeFormatterStrategy('Y-m-d');
        $hydrated = $strategy->hydrate($value);
        $this->assertSame($value, $hydrated);
    }
}
