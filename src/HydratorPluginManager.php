<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Zend\Hydrator;

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
     * Default aliases
     *
     * @var string[]
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
     * @var string[]|callable[]
     */
    protected $factories = [
        ArraySerializable::class  => InvokableFactory::class,
        ClassMethods::class       => InvokableFactory::class,
        DelegatingHydrator::class => DelegatingHydratorFactory::class,
        ObjectProperty::class     => InvokableFactory::class,
        Reflection::class         => InvokableFactory::class,
    ];

    /**
     * Whether or not to share by default (v3)
     *
     * @var bool
     */
    protected $sharedByDefault = false;

    /**
     * Whether or not to share by default (v2)
     *
     * @var bool
     */
    protected $shareByDefault = false;

    /**
     * {inheritDoc}
     */
    protected $instanceOf = HydratorInterface::class;

    /**
     * Validate the plugin is of the expected type.
     *
     * Checks that the filter loaded is a valid hydrator.
     *
     * @param mixed $instance
     * @throws InvalidServiceException
     */
    public function validate($instance)
    {
        if ($instance instanceof $this->instanceOf) {
            // we're okay
            return;
        }

        throw new InvalidServiceException(sprintf(
            'Plugin of type %s is invalid; must implement %s',
            (is_object($instance) ? get_class($instance) : gettype($instance)),
            HydratorInterface::class
        ));
    }
}
