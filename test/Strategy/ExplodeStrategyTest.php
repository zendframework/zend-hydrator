<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

declare(strict_types=1);

namespace ZendTest\Hydrator\Strategy;

use PHPUnit\Framework\TestCase;
use TypeError;
use Zend\Hydrator\Strategy\Exception\InvalidArgumentException;
use Zend\Hydrator\Strategy\ExplodeStrategy;

/**
 * Tests for {@see ExplodeStrategy}
 *
 * @covers \Zend\Hydrator\Strategy\ExplodeStrategy
 */
class ExplodeStrategyTest extends TestCase
{
    /**
     * @dataProvider getValidHydratedValues
     *
     * @param string   $expected
     * @param string   $delimiter
     * @param string[] $extractValue
     */
    public function testExtract($expected, $delimiter, $extractValue)
    {
        $strategy = new ExplodeStrategy($delimiter);

        if (is_numeric($expected)) {
            $this->assertEquals($expected, $strategy->extract($extractValue));
        } else {
            $this->assertSame($expected, $strategy->extract($extractValue));
        }
    }

    public function testGetExceptionWithInvalidArgumentOnExtraction()
    {
        $strategy = new ExplodeStrategy();

        $this->expectException(InvalidArgumentException::class);

        $strategy->extract('');
    }

    public function testGetEmptyArrayWhenHydratingNullValue()
    {
        $strategy = new ExplodeStrategy();

        $this->assertSame([], $strategy->hydrate(null));
    }

    public function testGetExceptionWithEmptyDelimiter()
    {
        $this->expectException(InvalidArgumentException::class);

        new ExplodeStrategy('');
    }

    public function testGetExceptionWithInvalidDelimiter()
    {
        $this->expectException(TypeError::class);

        new ExplodeStrategy([]);
    }

    public function testHydrateWithExplodeLimit()
    {
        $strategy = new ExplodeStrategy('-', 2);
        $this->assertSame(['foo', 'bar-baz-bat'], $strategy->hydrate('foo-bar-baz-bat'));

        $strategy = new ExplodeStrategy('-', 3);
        $this->assertSame(['foo', 'bar', 'baz-bat'], $strategy->hydrate('foo-bar-baz-bat'));
    }

    public function testHydrateWithInvalidScalarType()
    {
        $strategy = new ExplodeStrategy();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Zend\Hydrator\Strategy\ExplodeStrategy::hydrate expects argument 1 to be string,'
            . ' array provided instead'
        );

        $strategy->hydrate([]);
    }

    public function testHydrateWithInvalidObjectType()
    {
        $strategy = new ExplodeStrategy();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Zend\Hydrator\Strategy\ExplodeStrategy::hydrate expects argument 1 to be string,'
            . ' stdClass provided instead'
        );

        $strategy->hydrate(new \stdClass());
    }

    public function testExtractWithInvalidObjectType()
    {
        $strategy = new ExplodeStrategy();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Zend\Hydrator\Strategy\ExplodeStrategy::extract expects argument 1 to be array,'
            . ' stdClass provided instead'
        );

        $strategy->extract(new \stdClass());
    }

    /**
     * @dataProvider getValidHydratedValues
     *
     * @param mixed    $value
     * @param string   $delimiter
     * @param string[] $expected
     */
    public function testHydration($value, $delimiter, array $expected)
    {
        $strategy = new ExplodeStrategy($delimiter);

        $this->assertSame($expected, $strategy->hydrate($value));
    }

    /**
     * Data provider
     *
     * @return mixed[][]
     */
    public function getValidHydratedValues()
    {
        // @codingStandardsIgnoreStart
        return [
            'null-comma'                              => [null, ',', []],
            'empty-comma'                             => ['', ',', ['']],
            'string without delimiter-comma'          => ['foo', ',', ['foo']],
            'string with delimiter-comma'             => ['foo,bar', ',', ['foo', 'bar']],
            'string with delimiter-period'            => ['foo.bar', '.', ['foo', 'bar']],
            'string with mismatched delimiter-comma'  => ['foo.bar', ',', ['foo.bar']],
            'integer-comma'                           => [123, ',', ['123']],
            'integer-numeric delimiter'               => [123, '2', ['1', '3']],
            'integer with mismatched delimiter-comma' => [123.456, ',', ['123.456']],
            'float-period'                            => [123.456, '.', ['123', '456']],
            'string containing null-comma'            => ['foo,bar,dev,null', ',', ['foo', 'bar', 'dev', 'null']],
            'string containing null-semicolon'        => ['foo;bar;dev;null', ';', ['foo', 'bar', 'dev', 'null']],
        ];
        // @codingStandardsIgnoreEnd
    }
}
