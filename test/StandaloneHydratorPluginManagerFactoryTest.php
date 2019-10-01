<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace ZendTest\Hydrator;

use PHPUnit\Framework\Error\Notice;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Zend\Hydrator\ArraySerializable;
use Zend\Hydrator\ArraySerializableHydrator;
use Zend\Hydrator\ClassMethods;
use Zend\Hydrator\ClassMethodsHydrator;
use Zend\Hydrator\DelegatingHydrator;
use Zend\Hydrator\DelegatingHydratorFactory;
use Zend\Hydrator\ObjectProperty;
use Zend\Hydrator\ObjectPropertyHydrator;
use Zend\Hydrator\Reflection;
use Zend\Hydrator\ReflectionHydrator;
use Zend\Hydrator\StandaloneHydratorPluginManager;
use Zend\Hydrator\StandaloneHydratorPluginManagerFactory;

use function sprintf;

class StandaloneHydratorPluginManagerFactoryTest extends TestCase
{
    private const MESSAGE_DEFAULT_SERVICES = 'Missing the service %s';

    protected function setUp() : void
    {
        $this->factory   = new StandaloneHydratorPluginManagerFactory();
        $this->container = $this->prophesize(ContainerInterface::class);
    }

    public function assertDefaultServices(
        StandaloneHydratorPluginManager $manager,
        string $message = self::MESSAGE_DEFAULT_SERVICES
    ) {
        $this->assertTrue($manager->has('ArraySerializable'), sprintf($message, 'ArraySerializable'));
        $this->assertTrue($manager->has('ArraySerializableHydrator'), sprintf($message, 'ArraySerializableHydrator'));
        $this->assertTrue($manager->has(ArraySerializable::class), sprintf($message, ArraySerializable::class));
        $this->assertTrue(
            $manager->has(ArraySerializableHydrator::class),
            sprintf($message, ArraySerializableHydrator::class)
        );

        $this->assertTrue($manager->has('ClassMethods'), sprintf($message, 'ClassMethods'));
        $this->assertTrue($manager->has('ClassMethodsHydrator'), sprintf($message, 'ClassMethodsHydrator'));
        $this->assertTrue($manager->has(ClassMethods::class), sprintf($message, ClassMethods::class));
        $this->assertTrue($manager->has(ClassMethodsHydrator::class), sprintf($message, ClassMethodsHydrator::class));

        $this->assertTrue($manager->has('DelegatingHydrator'), sprintf($message, 'DelegatingHydrator'));
        $this->assertTrue($manager->has(DelegatingHydrator::class), sprintf($message, DelegatingHydrator::class));

        $this->assertTrue($manager->has('ObjectProperty'), sprintf($message, 'ObjectProperty'));
        $this->assertTrue($manager->has('ObjectPropertyHydrator'), sprintf($message, 'ObjectPropertyHydrator'));
        $this->assertTrue($manager->has(ObjectProperty::class), sprintf($message, ObjectProperty::class));
        $this->assertTrue(
            $manager->has(ObjectPropertyHydrator::class),
            sprintf($message, ObjectPropertyHydrator::class)
        );

        $this->assertTrue($manager->has('Reflection'), sprintf($message, 'Reflection'));
        $this->assertTrue($manager->has('ReflectionHydrator'), sprintf($message, 'ReflectionHydrator'));
        $this->assertTrue($manager->has(Reflection::class), sprintf($message, Reflection::class));
        $this->assertTrue($manager->has(ReflectionHydrator::class), sprintf($message, ReflectionHydrator::class));
    }

    public function testCreatesPluginManagerWithDefaultServices()
    {
        $manager = ($this->factory)($this->container->reveal());
        $this->assertDefaultServices($manager);
    }
}
