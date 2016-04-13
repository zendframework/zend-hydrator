<?php
/**
 * @link      http://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Zend\Hydrator;

class Module
{
    /**
     * Return default zend-hydrator configuration for zend-mvc applications.
     */
    public function getConfig()
    {
        $provider = new ConfigProvider();

        return [
            'service_manager' => $provider->getDependencyConfig(),
        ];
    }

    /**
     * Register a specification for the HydratorManager with the ServiceListener.
     *
     * @param \Zend\ModuleManager\ModuleEvent
     * @return void
     */
    public function init($event)
    {
        $container = $event->getParam('ServiceManager');
        $serviceListener = $container->get('ServiceListener');

        $serviceListener->addServiceManager(
            'HydratorManager',
            'hydrators',
            'Zend\Hydrator\HydratorProviderInterface',
            'getHydratorConfig'
        );
    }
}
