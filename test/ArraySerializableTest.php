<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendTest\Hydrator;

use Zend\Hydrator\Exception\BadMethodCallException;
use Zend\Hydrator\ArraySerializable;
use ZendTest\Hydrator\TestAsset\ArraySerializable as ArraySerializableAsset;

/**
 * Unit tests for {@see ArraySerializable}
 *
 * @covers \Zend\Hydrator\ArraySerializable
 */
class ArraySerializableTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ArraySerializable
     */
    protected $hydrator;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $this->hydrator = new ArraySerializable();
    }

    /**
     * Verify that we get an exception when trying to extract on a non-object
     */
    public function testHydratorExtractThrowsExceptionOnNonObjectParameter()
    {
        $this->setExpectedException(
            BadMethodCallException::class,
            'Zend\Hydrator\ArraySerializable::extract expects the provided object to implement getArrayCopy()'
        );
        $this->hydrator->extract('thisIsNotAnObject');
    }

    /**
     * Verify that we get an exception when trying to hydrate a non-object
     */
    public function testHydratorHydrateThrowsExceptionOnNonObjectParameter()
    {
        $this->setExpectedException(
            BadMethodCallException::class,
            'Zend\Hydrator\ArraySerializable::hydrate expects the provided object to implement'
            . ' exchangeArray() or populate()'
        );
        $this->hydrator->hydrate(['some' => 'data'], 'thisIsNotAnObject');
    }

    /**
     * Verifies that we can extract from an ArraySerializableInterface
     */
    public function testCanExtractFromArraySerializableObject()
    {
        $this->assertSame(
            [
                'foo'   => 'bar',
                'bar'   => 'foo',
                'blubb' => 'baz',
                'quo'   => 'blubb',
            ],
            $this->hydrator->extract(new ArraySerializableAsset())
        );
    }

    /**
     * Verifies we can hydrate an ArraySerializableInterface
     */
    public function testCanHydrateToArraySerializableObject()
    {
        $data = [
            'foo'   => 'bar1',
            'bar'   => 'foo1',
            'blubb' => 'baz1',
            'quo'   => 'blubb1',
        ];
        $object = $this->hydrator->hydrate($data, new ArraySerializableAsset());

        $this->assertSame($data, $object->getArrayCopy());
    }
}
