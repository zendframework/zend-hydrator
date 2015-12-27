<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendTest\Hydrator\NamingStrategy\UnderscoreNamingStrategy;
use Zend\Hydrator\NamingStrategy\UnderscoreNamingStrategy\UnderscoreToCamelCaseFilter;


/**
 * Test class for Zend\Filter\Word\SeparatorToCamelCase.
 *
 * @group      Zend_Filter
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
        $filtered = $filter->filter($string);

        $this->assertNotEquals($string, $filtered);
        $this->assertEquals($expected, $filtered);
    }

    public function nonUnicodeProvider(){
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
            'array input' => [
                ['first_input', 'second_input'],
                ['firstInput', 'secondInput']
            ]
        ];
    }


    /**
     * @group ZF-10517
     */
    public function testFilterStudlyCasesUnicodeStrings()
    {
        if (!extension_loaded('mbstring')) {
            $this->markTestSkipped('Extension mbstring not available');
        }

        $string   = 'test_šuma';
        $filter   = new UnderscoreToCamelCaseFilter;
        $filtered = $filter->filter($string);

        $this->assertNotEquals($string, $filtered);
        $this->assertEquals('testŠuma', $filtered);
    }


    public function returnUnfilteredDataProvider()
    {
        return [
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