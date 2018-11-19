<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Zend\Hydrator;

use Psr\Container\ContainerInterface;

class DelegatingHydratorFactory
{
    /**
     * Creates DelegatingHydrator
     */
    public function __invoke(ContainerInterface $container) : DelegatingHydrator
    {
        $container = $this->marshalHydratorPluginManager($container);
        return new DelegatingHydrator($container);
    }

    /**
     * Locate and return a HydratorPluginManager instance.
     *
     * @param ContainerInterface $container
     * @return HydratorPluginManager
     */
    private function marshalHydratorPluginManager(ContainerInterface $container) : HydratorPluginManager
    {
        // Already one? Return it.
        if ($container instanceof HydratorPluginManager) {
            return $container;
        }

        // As typically registered with v3 (FQCN)
        if ($container->has(HydratorPluginManager::class)) {
            return $container->get(HydratorPluginManager::class);
        }

        // As registered by zend-mvc
        if ($container->has('HydratorManager')) {
            return $container->get('HydratorManager');
        }

        // Fallback: create one
        return new HydratorPluginManager($container);
    }
}
