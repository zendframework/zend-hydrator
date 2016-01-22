<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Zend\Hydrator;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DelegatingHydratorFactory implements FactoryInterface
{
    /**
     * Creates DelegatingHydrator (v2)
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return DelegatingHydrator
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, '');
    }

    /**
     * Creates DelegatingHydrator (v3)
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return DelegatingHydrator
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        // Assume that this factory is registered with the HydratorManager,
        // and just pass it directly on.
        return new DelegatingHydrator($container);
    }
}
