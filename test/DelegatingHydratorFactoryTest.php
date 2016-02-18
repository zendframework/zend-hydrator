<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendTest\Hydrator;

use Interop\Container\ContainerInterface;
use ReflectionProperty;
use Zend\Hydrator\DelegatingHydrator;
use Zend\Hydrator\DelegatingHydratorFactory;
use Zend\Hydrator\HydratorPluginManager;
use Zend\ServiceManager\ServiceLocatorInterface;

class DelegatingHydratorFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testV2Factory()
    {
        $hydrators = $this->prophesize(HydratorPluginManager::class)->reveal();
        $prophesy = $this->prophesize(ServiceLocatorInterface::class);
        $prophesy->willImplement(ContainerInterface::class);
        $prophesy->has(HydratorPluginManager::class)->willReturn(true);
        $prophesy->get(HydratorPluginManager::class)->willReturn($hydrators);

        $factory = new DelegatingHydratorFactory();
        $this->assertInstanceOf(
            DelegatingHydrator::class,
            $factory->createService($prophesy->reveal())
        );
    }

    public function testFactoryUsesContainerToSeedDelegatingHydratorWhenItIsAHydratorPluginManager()
    {
        $hydrators = $this->prophesize(HydratorPluginManager::class)->reveal();
        $factory = new DelegatingHydratorFactory();

        $hydrator = $factory($hydrators, '');
        $this->assertInstanceOf(DelegatingHydrator::class, $hydrator);
        $this->assertAttributeSame($hydrators, 'hydrators', $hydrator);
    }

    public function testFactoryUsesHydratorPluginManagerServiceFromContainerToSeedDelegatingHydratorWhenAvailable()
    {
        $hydrators = $this->prophesize(HydratorPluginManager::class)->reveal();
        $container = $this->prophesize(ContainerInterface::class);
        $container->has(HydratorPluginManager::class)->willReturn(true);
        $container->get(HydratorPluginManager::class)->willReturn($hydrators);

        $factory = new DelegatingHydratorFactory();

        $hydrator = $factory($container->reveal(), '');
        $this->assertInstanceOf(DelegatingHydrator::class, $hydrator);
        $this->assertAttributeSame($hydrators, 'hydrators', $hydrator);
    }

    public function testFactoryUsesHydratorManagerServiceFromContainerToSeedDelegatingHydratorWhenAvailable()
    {
        $hydrators = $this->prophesize(HydratorPluginManager::class)->reveal();
        $container = $this->prophesize(ContainerInterface::class);
        $container->has(HydratorPluginManager::class)->willReturn(false);
        $container->has('HydratorManager')->willReturn(true);
        $container->get('HydratorManager')->willReturn($hydrators);

        $factory = new DelegatingHydratorFactory();

        $hydrator = $factory($container->reveal(), '');
        $this->assertInstanceOf(DelegatingHydrator::class, $hydrator);
        $this->assertAttributeSame($hydrators, 'hydrators', $hydrator);
    }

    public function testFactoryCreatesHydratorPluginManagerToSeedDelegatingHydratorAsFallback()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->has(HydratorPluginManager::class)->willReturn(false);
        $container->has('HydratorManager')->willReturn(false);

        $factory = new DelegatingHydratorFactory();

        $hydrator = $factory($container->reveal(), '');
        $this->assertInstanceOf(DelegatingHydrator::class, $hydrator);

        $r = new ReflectionProperty($hydrator, 'hydrators');
        $r->setAccessible(true);
        $hydrators = $r->getValue($hydrator);

        $this->assertInstanceOf(HydratorPluginManager::class, $hydrators);

        $property = method_exists($hydrators, 'configure')
            ? 'creationContext' // v3
            : 'serviceLocator'; // v2

        $this->assertAttributeSame($container->reveal(), $property, $hydrators);
    }
}
