<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendTest\Hydrator\NamingStrategy\UnderscoreNamingStrategy;

use ReflectionClass;
use Zend\Hydrator\NamingStrategy\UnderscoreNamingStrategy\UnderscoreToCamelCaseFilter;

/**
 * Tests for {@see UnderscoreToCamelCaseFilter}
 *
 * @covers Zend\Hydrator\NamingStrategy\UnderscoreNamingStrategy\UnderscoreToCamelCaseFilter
 */
class UnderscoreToCamelCaseFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider nonUnicodeProvider
     * @param string $string
     * @param string $expected
     */
    public function testFilterCamelCasesNonUnicodeStrings($string, $expected)
    {
        $filter   = new UnderscoreToCamelCaseFilter();

        $reflectionClass = new ReflectionClass($filter);
        $property = $reflectionClass->getProperty('pcreUnicodeSupport');
        $property->setAccessible(true);
        $property->setValue($filter, false);

        $filtered = $filter->filter($string);

        $this->assertNotEquals($string, $filtered);
        $this->assertEquals($expected, $filtered);
    }

    public function nonUnicodeProvider()
    {
        return [
            'one word' => [
                'Studly',
                'studly'
            ],
            'multiple words' => [
                'studly_cases_me',
                'studlyCasesMe'
            ],
            'alphanumeric' => [
                'one_2_three',
                'one2Three'
            ],
        ];
    }

    /**
     * @dataProvider unicodeProvider
     * @param string $string
     * @param string $expected
     */
    public function testFilterCamelCasesUnicodeStrings($string, $expected)
    {
        if (!extension_loaded('mbstring')) {
            $this->markTestSkipped('Extension mbstring not available');
        }

        $filter   = new UnderscoreToCamelCaseFilter();
        $filtered = $filter->filter($string);

        $this->assertNotEquals($string, $filtered);
        $this->assertEquals($expected, $filtered);
    }

    public function unicodeProvider()
    {
        return [
            'uppercase first letter' => [
                'Camel',
                'camel'
            ],
            'multiple words' => [
                'studly_cases_me',
                'studlyCasesMe'
            ],
            'alphanumeric' => [
                'one_2_three',
                'one2Three'
            ],
            'unicode character' => [
                'test_Šuma',
                'testŠuma'
            ],
            'unicode character [ZF-10517]' => [
                'test_šuma',
                'testŠuma'
            ]
        ];
    }

    /**
     * @dataProvider unicodeWithoutMbStringsProvider
     * @param string $string
     * @param string $expected
     */
    public function testFilterCamelCasesUnicodeStringsWithoutMbStrings(
        $string,
        $expected
    ) {

        $filter   = new UnderscoreToCamelCaseFilter();

        $reflectionClass = new ReflectionClass($filter);
        $property = $reflectionClass->getProperty('mbStringSupport');
        $property->setAccessible(true);
        $property->setValue($filter, false);

        $filtered = $filter->filter($string);
        $this->assertEquals($expected, $filtered);
    }

    public function unicodeWithoutMbStringsProvider()
    {
        return [
            'multiple words' => [
                'studly_cases_me',
                'studlyCasesMe'
            ],
            'alphanumeric' => [
                'one_2_three',
                'one2Three'
            ],
            'uppercase unicode character' => [
                'test_Šuma',
                'testŠuma'
            ],
            'lowercase unicode character' => [
                'test_šuma',
                'test_šuma'
            ]
        ];
    }

    public function returnUnfilteredDataProvider()
    {
        return [
            ['foo'],
            [null],
            [new \stdClass()]
        ];
    }

    /**
     * @dataProvider returnUnfilteredDataProvider
     * @return void
     */
    public function testReturnUnfiltered($input)
    {
        $filter = new UnderscoreToCamelCaseFilter();

        $this->assertEquals($input, $filter->filter($input));
    }
}
