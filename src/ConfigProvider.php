<?php
/**
 * @link      http://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2016-2018 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

declare(strict_types=1);

namespace Zend\Hydrator;

class ConfigProvider
{
    /**
     * Return configuration for this component.
     */
    public function __invoke() : array
    {
        return [
            'dependencies' => $this->getDependencyConfig(),
        ];
    }

    /**
     * Return dependency mappings for this component.
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
