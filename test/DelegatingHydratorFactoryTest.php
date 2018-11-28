<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace ZendTest\Hydrator;

use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;
use Zend\Hydrator\DelegatingHydrator;
use Zend\Hydrator\DelegatingHydratorFactory;
use Zend\Hydrator\HydratorPluginManager;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @covers Zend\Hydrator\DelegatingHydratorFactory<extended>
 */
class DelegatingHydratorFactoryTest extends TestCase
{
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
        $this->assertAttributeSame($container->reveal(), 'creationContext', $hydrators);
    }
}
