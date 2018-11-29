<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace ZendTest\Hydrator\NamingStrategy;

use PHPUnit\Framework\TestCase;
use Zend\Hydrator\Exception;
use Zend\Hydrator\NamingStrategy\MapNamingStrategy;

/**
 * Tests for {@see MapNamingStrategy}
 *
 * @covers \Zend\Hydrator\NamingStrategy\MapNamingStrategy
 */
class MapNamingStrategyTest extends TestCase
{
    public function testConstructorRaisesExceptionIfNoExtractionOrHydrationMapProvided()
    {
        $this->expectException(Exception\InvalidArgumentException::class);
        $this->expectExceptionMessage('requires one or both of an array $hydrationMap and array $extractionMap');

        new MapNamingStrategy();
    }

    public function invalidMapValues() : iterable
    {
        yield 'null'       => [null];
        yield 'true'       => [true];
        yield 'false'      => [false];
        yield 'zero-float' => [0.0];
        yield 'float'      => [1.1];
        yield 'array'      => [['foo']];
        yield 'object'     => [(object) ['foo' => 'bar']];
    }

    /**
     * @dataProvider invalidMapValues
     * @param mixed $invalidValue
     */
    public function testConstructorRaisesExceptionWhenFlippingHydrationMapForInvalidValues($invalidValue)
    {
        $this->expectException(Exception\InvalidArgumentException::class);
        $this->expectExceptionMessage('can not be flipped');

        new MapNamingStrategy(['foo' => $invalidValue]);
    }

    /**
     * @dataProvider invalidMapValues
     * @param mixed $invalidValue
     */
    public function testConstructorRaisesExceptionWhenFlippingExtractionMapForInvalidValues($invalidValue)
    {
        $this->expectException(Exception\InvalidArgumentException::class);
        $this->expectExceptionMessage('can not be flipped');

        new MapNamingStrategy(null, ['foo' => $invalidValue]);
    }

    public function testExtractReturnsVerbatimWhenEmptyExtractionMapProvided()
    {
        $strategy = new MapNamingStrategy(null, []);
        $this->assertEquals('some_stuff', $strategy->extract('some_stuff'));
    }

    public function testHydrateReturnsVerbatimWhenEmptyHydrationMapProvided()
    {
        $strategy = new MapNamingStrategy([]);
        $this->assertEquals('some_stuff', $strategy->hydrate('some_stuff'));
    }

    public function testExtractUsesProvidedExtractionMap()
    {
        $strategy = new MapNamingStrategy(null, ['stuff3' => 'stuff4']);
        $this->assertEquals('stuff4', $strategy->extract('stuff3'));
    }

    public function testExtractUsesFlippedHydrationMapWhenNoExtractionMapProvided()
    {
        $strategy = new MapNamingStrategy(['stuff3' => 'stuff4']);
        $this->assertEquals('stuff3', $strategy->extract('stuff4'));
    }

    public function testHydrateUsesProvidedHydrationMap()
    {
        $strategy = new MapNamingStrategy(['stuff3' => 'stuff4']);
        $this->assertEquals('stuff4', $strategy->hydrate('stuff3'));
    }

    public function testHydrateUsesFlippedExtractionMapWhenNoHydrationMapProvided()
    {
        $strategy = new MapNamingStrategy(null, ['foo' => 'bar']);
        $this->assertEquals('foo', $strategy->hydrate('bar'));
    }
}
