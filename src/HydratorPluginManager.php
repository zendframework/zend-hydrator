<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Zend\Hydrator;

use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Exception\InvalidServiceException;
use Zend\ServiceManager\Factory\InvokableFactory;

/**
 * Plugin manager implementation for hydrators.
 *
 * Enforces that adapters retrieved are instances of HydratorInterface
 */
class HydratorPluginManager extends AbstractPluginManager
{
    /**
     * Whether or not to share by default
     *
     * @var bool
     */
    protected $sharedByDefault = false;

    /**
     * Default aliases
     *
     * @var array
     */
    protected $aliases = [
        'arrayserializable'  => ArraySerializable::class,
        'arraySerializable'  => ArraySerializable::class,
        'ArraySerializable'  => ArraySerializable::class,
        'classmethods'       => ClassMethods::class,
        'classMethods'       => ClassMethods::class,
        'ClassMethods'       => ClassMethods::class,
        'delegatinghydrator' => DelegatingHydrator::class,
        'delegatingHydrator' => DelegatingHydrator::class,
        'DelegatingHydrator' => DelegatingHydrator::class,
        'objectproperty'     => ObjectProperty::class,
        'objectProperty'     => ObjectProperty::class,
        'ObjectProperty'     => ObjectProperty::class,
        'reflection'         => Reflection::class,
        'Reflection'         => Reflection::class,
    ];

    /**
     * Default factory-based adapters
     *
     * @var array
     */
    protected $factories = [
        ArraySerializable::class  => InvokableFactory::class,
        ClassMethods::class       => InvokableFactory::class,
        DelegatingHydrator::class => DelegatingHydratorFactory::class,
        ObjectProperty::class     => InvokableFactory::class,
        Reflection::class         => InvokableFactory::class,
    ];

    /**
     * Validate the plugin is of the expected type (v3).
     *
     * Checks that the filter loaded is either a valid callback or an instance
     * of FilterInterface.
     *
     * @param mixed $instance
     * @throws InvalidServiceException
     */
    public function validate($instance)
    {
        if ($instance instanceof HydratorInterface) {
            // we're okay
            return;
        }

        throw new Exception\RuntimeException(sprintf(
            'Plugin of type %s is invalid; must implement Zend\Hydrator\HydratorInterface',
            (is_object($instance) ? get_class($instance) : gettype($instance))
        ));
    }

    /**
     * {@inheritDoc} (v2)
     */
    public function validatePlugin($plugin)
    {
        $this->validate($plugin);
    }
}
