<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Zend\Hydrator;

use Psr\Container\ContainerInterface;

final class StandaloneHydratorPluginManagerFactory
{
    public function __invoke(ContainerInterface $container) : StandaloneHydratorPluginManager
    {
        return new StandaloneHydratorPluginManager();
    }
}
