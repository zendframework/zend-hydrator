<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace ZendTest\Hydrator\Filter;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Zend\Hydrator\Filter\OptionalParametersFilter;

/**
 * Unit tests for {@see OptionalParametersFilter}
 *
 * @covers \Zend\Hydrator\Filter\OptionalParametersFilter
 */
class OptionalParametersFilterTest extends TestCase
{
    /**
     * @var OptionalParametersFilter
     */
    protected $filter;

    /**
     * {@inheritDoc}
     */
    protected function setUp() : void
    {
        $this->filter = new OptionalParametersFilter();
    }

    /**
     * Verifies a list of methods against expected results
     *
     * @param string $method
     * @param bool   $expectedResult
     *
     * @dataProvider methodProvider
     */
    public function testMethods($method, $expectedResult)
    {
        $this->assertSame($expectedResult, $this->filter->filter($method));
    }

    /**
     * Verifies a list of methods against expected results over subsequent calls, checking
     * that the filter behaves consistently regardless of cache optimizations
     *
     * @param string $method
     * @param bool   $expectedResult
     *
     * @dataProvider methodProvider
     */
    public function testMethodsOnSubsequentCalls($method, $expectedResult)
    {
        for ($i = 0; $i < 5; $i += 1) {
            $this->assertSame($expectedResult, $this->filter->filter($method));
        }
    }

    public function testTriggersExceptionOnUnknownMethod()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->filter->filter(__CLASS__ . '::' . 'nonExistingMethod');
    }

    /**
     * Provides a list of methods to be checked against the filter
     *
     * @return array
     */
    public function methodProvider()
    {
        return [
            [__CLASS__ . '::' . 'methodWithoutParameters', true],
            [__CLASS__ . '::' . 'methodWithSingleMandatoryParameter', false],
            [__CLASS__ . '::' . 'methodWithSingleOptionalParameter', true],
            [__CLASS__ . '::' . 'methodWithMultipleMandatoryParameters', false],
            [__CLASS__ . '::' . 'methodWithMultipleOptionalParameters', true],
        ];
    }

    /**
     * Test asset method
     */
    public function methodWithoutParameters()
    {
    }

    /**
     * Test asset method
     */
    public function methodWithSingleMandatoryParameter($parameter)
    {
    }

    /**
     * Test asset method
     */
    public function methodWithSingleOptionalParameter($parameter = null)
    {
    }

    /**
     * Test asset method
     */
    public function methodWithMultipleMandatoryParameters($parameter, $otherParameter)
    {
    }

    /**
     * Test asset method
     */
    public function methodWithMultipleOptionalParameters($parameter = null, $otherParameter = null)
    {
    }
}
