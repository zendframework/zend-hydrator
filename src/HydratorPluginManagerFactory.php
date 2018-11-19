<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Zend\Hydrator;

use Psr\Container\ContainerInterface;
use Zend\ServiceManager\Config;

class HydratorPluginManagerFactory
{
    /**
     * {@inheritDoc}
     */
    public function __invoke(ContainerInterface $container, string $name, array $options = []) : HydratorPluginManager
    {
        $pluginManager = new HydratorPluginManager($container, $options ?: []);

        // If this is in a zend-mvc application, the ServiceListener will inject
        // merged configuration during bootstrap.
        if ($container->has('ServiceListener')) {
            return $pluginManager;
        }

        // If we do not have a config service, nothing more to do
        if (! $container->has('config')) {
            return $pluginManager;
        }

        $config = $container->get('config');

        // If we do not have hydrators configuration, nothing more to do
        if (! isset($config['hydrators']) || ! is_array($config['hydrators'])) {
            return $pluginManager;
        }

        // Wire service configuration for hydrators
        (new Config($config['hydrators']))->configureServiceManager($pluginManager);

        return $pluginManager;
    }
}
