<?php
/**
 * @link      http://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendTest\Hydrator;

use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use Zend\Hydrator\HydratorInterface;
use Zend\Hydrator\HydratorPluginManager;
use Zend\Hydrator\HydratorPluginManagerFactory;
use Zend\Hydrator\Reflection;
use Zend\ServiceManager\ServiceLocatorInterface;

class HydratorPluginManagerFactoryTest extends TestCase
{
    public function testFactoryReturnsPluginManager()
    {
        $container = $this->prophesize(ContainerInterface::class)->reveal();
        $factory = new HydratorPluginManagerFactory();

        $hydrators = $factory($container, HydratorPluginManagerFactory::class);
        $this->assertInstanceOf(HydratorPluginManager::class, $hydrators);

        if (method_exists($hydrators, 'configure')) {
            // zend-servicemanager v3
            $this->assertAttributeSame($container, 'creationContext', $hydrators);
        } else {
            // zend-servicemanager v2
            $this->assertSame($container, $hydrators->getServiceLocator());
        }
    }

    /**
     * @depends testFactoryReturnsPluginManager
     */
    public function testFactoryConfiguresPluginManagerUnderContainerInterop()
    {
        $container = $this->prophesize(ContainerInterface::class)->reveal();
        $hydrator = $this->prophesize(HydratorInterface::class)->reveal();

        $factory = new HydratorPluginManagerFactory();
        $hydrators = $factory($container, HydratorPluginManagerFactory::class, [
            'services' => [
                'test' => $hydrator,
            ],
        ]);
        $this->assertSame($hydrator, $hydrators->get('test'));
    }

    /**
     * @depends testFactoryReturnsPluginManager
     */
    public function testFactoryConfiguresPluginManagerUnderServiceManagerV2()
    {
        $container = $this->prophesize(ServiceLocatorInterface::class);
        $container->willImplement(ContainerInterface::class);

        $hydrator = $this->prophesize(HydratorInterface::class)->reveal();

        $factory = new HydratorPluginManagerFactory();
        $factory->setCreationOptions([
            'services' => [
                'test' => $hydrator,
            ],
        ]);

        $hydrators = $factory->createService($container->reveal());
        $this->assertSame($hydrator, $hydrators->get('test'));
    }

    public function testConfiguresHydratorServicesWhenFound()
    {
        $hydrator = $this->prophesize(HydratorInterface::class)->reveal();
        $config = [
            'hydrators' => [
                'aliases' => [
                    'test' => Reflection::class,
                ],
                'factories' => [
                    'test-too' => function ($container) use ($hydrator) {
                        return $hydrator;
                    },
                ],
            ],
        ];

        $container = $this->prophesize(ServiceLocatorInterface::class);
        $container->willImplement(ContainerInterface::class);

        $container->has('ServiceListener')->willReturn(false);
        $container->has('config')->willReturn(true);
        $container->get('config')->willReturn($config);

        $factory = new HydratorPluginManagerFactory();
        $hydrators = $factory($container->reveal(), 'HydratorManager');

        $this->assertInstanceOf(HydratorPluginManager::class, $hydrators);
        $this->assertTrue($hydrators->has('test'));
        $this->assertInstanceOf(Reflection::class, $hydrators->get('test'));
        $this->assertTrue($hydrators->has('test-too'));
        $this->assertSame($hydrator, $hydrators->get('test-too'));
    }

    public function testDoesNotConfigureHydratorServicesWhenServiceListenerPresent()
    {
        $hydrator = $this->prophesize(HydratorInterface::class)->reveal();
        $config = [
            'hydrators' => [
                'aliases' => [
                    'test' => Reflection::class,
                ],
                'factories' => [
                    'test-too' => function ($container) use ($hydrator) {
                        return $hydrator;
                    },
                ],
            ],
        ];

        $container = $this->prophesize(ServiceLocatorInterface::class);
        $container->willImplement(ContainerInterface::class);

        $container->has('ServiceListener')->willReturn(true);
        $container->has('config')->shouldNotBeCalled();
        $container->get('config')->shouldNotBeCalled();

        $factory = new HydratorPluginManagerFactory();
        $hydrators = $factory($container->reveal(), 'HydratorManager');

        $this->assertInstanceOf(HydratorPluginManager::class, $hydrators);
        $this->assertFalse($hydrators->has('test'));
        $this->assertFalse($hydrators->has('test-too'));
    }

    public function testDoesNotConfigureHydratorServicesWhenConfigServiceNotPresent()
    {
        $container = $this->prophesize(ServiceLocatorInterface::class);
        $container->willImplement(ContainerInterface::class);

        $container->has('ServiceListener')->willReturn(false);
        $container->has('config')->willReturn(false);
        $container->get('config')->shouldNotBeCalled();

        $factory = new HydratorPluginManagerFactory();
        $hydrators = $factory($container->reveal(), 'HydratorManager');

        $this->assertInstanceOf(HydratorPluginManager::class, $hydrators);
    }

    public function testDoesNotConfigureHydratorServicesWhenConfigServiceDoesNotContainHydratorsConfig()
    {
        $container = $this->prophesize(ServiceLocatorInterface::class);
        $container->willImplement(ContainerInterface::class);

        $container->has('ServiceListener')->willReturn(false);
        $container->has('config')->willReturn(true);
        $container->get('config')->willReturn(['foo' => 'bar']);

        $factory = new HydratorPluginManagerFactory();
        $hydrators = $factory($container->reveal(), 'HydratorManager');

        $this->assertInstanceOf(HydratorPluginManager::class, $hydrators);
        $this->assertFalse($hydrators->has('foo'));
    }
}
