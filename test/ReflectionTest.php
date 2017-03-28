<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendTest\Hydrator;

use PHPUnit\Framework\TestCase;
use stdClass;
use InvalidArgumentException;
use Zend\Hydrator\Reflection;

/**
 * Unit tests for {@see Reflection}
 *
 * @covers \Zend\Hydrator\Reflection
 */
class ReflectionTest extends TestCase
{
    /**
     * @var Reflection
     */
    protected $hydrator;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $this->hydrator = new Reflection();
    }

    public function testCanExtract()
    {
        $this->assertSame([], $this->hydrator->extract(new stdClass()));
    }

    public function testCanHydrate()
    {
        $object = new stdClass();

        $this->assertSame($object, $this->hydrator->hydrate(['foo' => 'bar'], $object));
    }

    public function testNotStringOrObjectOnExtract()
    {
        $this->setExpectedException(InvalidArgumentException::class, 'Input must be a string or an object.');

        $argument = (int) 1;
        $this->hydrator->extract($argument);
    }

    public function testNotStringOrObjectOnHydrate()
    {
        $this->setExpectedException(InvalidArgumentException::class, 'Input must be a string or an object.');

        $argument = (int) 1;
        $this->hydrator->hydrate([ 'foo' => 'bar' ], $argument);
    }
}
