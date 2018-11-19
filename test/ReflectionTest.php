<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

declare(strict_types=1);

namespace ZendTest\Hydrator;

use PHPUnit\Framework\TestCase;
use stdClass;
use TypeError;
use Zend\Hydrator\Reflection;

/**
 * Unit tests for {@see Reflection}
 *
 * @covers \Zend\Hydrator\Reflection
 */
class ReflectionTest extends TestCase
{
    use HydratorTestTrait;

    /**
     * @var Reflection
     */
    protected $hydrator;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
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

    public function testExtractRaisesExceptionForInvalidInput()
    {
        $argument = (int) 1;

        $this->expectException(TypeError::class);
        $this->expectExceptionMessage('must be an object');

        $this->hydrator->extract($argument);
    }

    public function testHydrateRaisesExceptionForInvalidArgument()
    {
        $argument = (int) 1;

        $this->expectException(TypeError::class);
        $this->expectExceptionMessage('must be an object');

        $this->hydrator->hydrate([ 'foo' => 'bar' ], $argument);
    }
}
