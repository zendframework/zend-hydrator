<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace ZendTest\Hydrator\Filter;

use PHPUnit\Framework\TestCase;
use Zend\Hydrator\Exception\InvalidArgumentException;
use Zend\Hydrator\Filter\NumberOfParameterFilter;

/**
 * Unit tests for {@see NumberOfParameterFilter}
 *
 * @covers \Zend\Hydrator\Filter\NumberOfParameterFilter
 */
class NumberOfParameterFilterTest extends TestCase
{
    /**
     * @group 6083
     */
    public function testArityZero()
    {
        $filter = new NumberOfParameterFilter();
        $this->assertTrue($filter->filter(__CLASS__ . '::methodWithNoParameters'));
        $this->assertFalse($filter->filter(__CLASS__ . '::methodWithOptionalParameters'));
    }

    /**
     * @group 6083
     */
    public function testArityOne()
    {
        $filter = new NumberOfParameterFilter(1);
        $this->assertFalse($filter->filter(__CLASS__ . '::methodWithNoParameters'));
        $this->assertTrue($filter->filter(__CLASS__ . '::methodWithOptionalParameters'));
    }

    /**
     * Verifies an InvalidArgumentException is thrown for a method that doesn't exist
     */
    public function testFilterPropertyDoesNotExist()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Method ZendTest\Hydrator\Filter\NumberOfParameterFilterTest::methodDoesNotExist does not exist'
        );
        $filter = new NumberOfParameterFilter(1);
        $filter->filter(__CLASS__ . '::methodDoesNotExist');
    }

    /**
     * Test asset method
     */
    public function methodWithOptionalParameters($parameter = 'foo')
    {
    }

    /**
     * Test asset method
     */
    public function methodWithNoParameters()
    {
    }
}
