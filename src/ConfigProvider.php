<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2016-2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Zend\Hydrator;

class ConfigProvider
{
    /**
     * Return configuration for this component.
     *
     * @return mixed[]
     */
    public function __invoke() : array
    {
        return [
            'dependencies' => $this->getDependencyConfig(),
        ];
    }

    /**
     * Return dependency mappings for this component.
     *
     * @return string[][]
     */
    public function getDependencyConfig() : array
    {
        return [
            'aliases' => [
                'HydratorManager' => HydratorPluginManager::class,
            ],
            'factories' => [
                HydratorPluginManager::class => HydratorPluginManagerFactory::class,
            ],
        ];
    }
}
