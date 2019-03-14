<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2018 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace ZendTest\Hydrator\NamingStrategy\UnderscoreNamingStrategy;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Zend\Hydrator\NamingStrategy\UnderscoreNamingStrategy\CamelCaseToUnderscoreFilter;

use function extension_loaded;

/**
 * Tests for {@see CamelCaseToUnderscoreFilter}
 *
 * @covers Zend\Hydrator\NamingStrategy\UnderscoreNamingStrategy\CamelCaseToUnderscoreFilter
 */
class CamelCaseToUnderscoreFilterTest extends TestCase
{
    /**
     * @dataProvider nonUnicodeProvider
     * @param string $string
     * @param string $expected
     */
    public function testFilterUnderscoresNonUnicodeStrings($string, $expected)
    {
        $filter   = new CamelCaseToUnderscoreFilter();

        $reflectionClass = new ReflectionClass($filter);
        $property = $reflectionClass->getProperty('pcreUnicodeSupport');
        $property->setAccessible(true);
        $property->setValue($filter, false);

        $filtered = $filter->filter($string);

        $this->assertNotEquals($string, $filtered);
        $this->assertEquals($expected, $filtered);
    }

    /**
     * @dataProvider unicodeProvider
     * @param string $string
     * @param string $expected
     */
    public function testFilterUnderscoresUnicodeStrings($string, $expected)
    {
        if (! extension_loaded('mbstring')) {
            $this->markTestSkipped('Extension mbstring not available');
        }

        $filter   = new CamelCaseToUnderscoreFilter();

        $filtered = $filter->filter($string);

        $this->assertNotEquals($string, $filtered);
        $this->assertEquals($expected, $filtered);
    }

    /**
     * @dataProvider unicodeProviderWithoutMbStrings
     * @param string $string
     * @param string $expected
     */
    public function testFilterUnderscoresUnicodeStringsWithoutMbStrings($string, $expected)
    {
        $filter   = new CamelCaseToUnderscoreFilter();

        $reflectionClass = new ReflectionClass($filter);
        $property = $reflectionClass->getProperty('mbStringSupport');
        $property->setAccessible(true);
        $property->setValue($filter, false);

        $filtered = $filter->filter($string);

        $this->assertNotEquals($string, $filtered);
        $this->assertEquals($expected, $filtered);
    }

    public function nonUnicodeProvider()
    {
        return [
            'upcased first letter' => [
                'Camel',
                'camel'
            ],
            'multiple words' => [
                'underscoresMe',
                'underscores_me'
            ],
            'alphanumeric' => [
                'one2Three',
                'one2_three'
            ],
            'multiple uppercased letters and underscores' => [
                'TheseAre_SOME_CamelCASEDWords',
                'these_are_some_camel_cased_words'
            ],
            'alphanumeric multiple up cases' => [
                'one2THR23ree',
                'one2_thr23ree'
            ],
            'lowercased alphanumeric' => [
                'bfd7b82e9cfceaa82704d1c1Foo',
                'bfd7b82e9cfceaa82704d1c1_foo',
            ],
        ];
    }

    public function unicodeProvider()
    {
        return [
            'upcased first letter' => [
                'Camel',
                'camel'
            ],
            'multiple words' => [
                'underscoresMe',
                'underscores_me'
            ],
            'alphanumeric' => [
                'one2Three',
                'one2_three'
            ],
            'multiple uppercased letters and underscores' => [
                'TheseAre_SOME_CamelCASEDWords',
                'these_are_some_camel_cased_words'
            ],
            'alphanumeric multiple up cases' => [
                'one2THR23ree',
                'one2_thr23ree'
            ],
            'unicode' => [
                'testŠuma',
                'test_šuma'
            ]
        ];
    }

    public function unicodeProviderWithoutMbStrings()
    {
        return [
            'upcased first letter' => [
                'Camel',
                'camel'
            ],
            'multiple words' => [
                'underscoresMe',
                'underscores_me'
            ],
            'alphanumeric' => [
                'one2Three',
                'one2_three'
            ],
            'multiple uppercased letters and underscores' => [
                'TheseAre_SOME_CamelCASEDWords',
                'these_are_some_camel_cased_words'
            ],
            'alphanumeric multiple up cases' => [
                'one2THR23ree',
                'one2_thr23ree'
            ],
            'unicode uppercase character' => [
                'testŠuma',
                'test_Šuma'
            ],
        ];
    }
}
