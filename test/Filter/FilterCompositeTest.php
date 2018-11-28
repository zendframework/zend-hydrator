<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace ZendTest\Hydrator\Filter;

use PHPUnit\Framework\TestCase;
use Zend\Hydrator\Filter\FilterComposite;
use Zend\Hydrator\Exception\InvalidArgumentException;
use Zend\Hydrator\Filter\GetFilter;
use Zend\Hydrator\Filter\HasFilter;
use Zend\Hydrator\Filter\IsFilter;
use Zend\Hydrator\Filter\NumberOfParameterFilter;

/**
 * Unit tests for {@see FilterComposite}
 *
 * @covers \Zend\Hydrator\Filter\FilterComposite
 */
class FilterCompositeTest extends TestCase
{
    /**
     * @dataProvider getDataProvider
     */
    public function testFilters($orFilters, $andFilters, $exceptionThrown)
    {
        if ($exceptionThrown) {
            if (empty($orFilters)) {
                $key = 'bar';
            } else {
                $key = 'foo';
            }

            $this->expectException(InvalidArgumentException::class);
            $this->expectExceptionMessage(
                sprintf(
                    'The value of %s should be either a callable or an ' .
                    'instance of Zend\Hydrator\Filter\FilterInterface',
                    $key
                )
            );
        }

        $filter = new FilterComposite($orFilters, $andFilters);

        foreach ($orFilters as $name => $value) {
            $this->assertTrue($filter->hasFilter($name));
        }
    }

    /**
     * @return array
     */
    public function getDataProvider()
    {
        return [
            [
                ['foo' => 'bar'],
                [],
                'exception' => true,
            ],
            [
                [],
                ['bar' => 'foo'],
                'exception' => true,
            ],
            [
                ['foo' => ''],
                ['bar' => ''],
                'exception' => true,
            ],
            [
                ['foo' => new HasFilter()],
                ['bar' => new GetFilter()],
                'exception' => false,
            ],
            [
                [
                    'foo1' => new HasFilter(),
                    'foo2' => new IsFilter(),
                ],
                [
                    'bar1' => new GetFilter(),
                    'bar2' => new NumberOfParameterFilter(),
                ],
                'exception' => false,
            ],
        ];
    }
}
