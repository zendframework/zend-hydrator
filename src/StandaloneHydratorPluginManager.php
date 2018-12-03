<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Zend\Hydrator;

use Psr\Container\ContainerInterface;

use function strtolower;

/**
 * Standalone hydrator manager.
 *
 * This class implements a standalone version of the HydratorPluginManager
 * that can be used anywhere a PSR-11 ContainerInterface is expected.
 *
 * It will load any hydrator implementation shipped in this package, and only
 * those hydrators shipped in this package, using:
 *
 * - The fully qualified class name.
 * - The class name minus the namespace.
 * - The fully qualified class name minus the "Hydrator" suffix (for BC
 *   compatibility with v2 names).
 * - The class name minus the namespace or the "Hydrator" suffix (for BC
 *   compatibility with v2 names).
 *
 * If you want to be able to configure additional services, you will need to
 * either install zend-servicemanager and use the HydratorPluginManager;
 * wire hydrators into your application container; or write your own
 * implementation.
 */
final class StandaloneHydratorPluginManager implements
    ContainerInterface,
    HydratorPluginManagerInterface
{
    /**
     * To allow using the short name (class name without namespace), this maps
     * the lowercase name to the FQCN. For hydrators that in previous versions
     * did not have the Hydrator suffix, it also maps the class name without
     * the suffix.
     *
     * @var array<string, string>
     */
    private $aliases = [
        'arrayserializable'         => ArraySerializableHydrator::class,
        ArraySerializable::class    => ArraySerializableHydrator::class,
        'arrayserializablehydrator' => ArraySerializableHydrator::class,
        ClassMethods::class         => ClassMethodsHydrator::class,
        'classmethods'              => ClassMethodsHydrator::class,
        'classmethodshydrator'      => ClassMethodsHydrator::class,
        'delegatinghydrator'        => DelegatingHydrator::class,
        ObjectProperty::class       => ObjectPropertyHydrator::class,
        'objectpropertyhydrator'    => ObjectPropertyHydrator::class,
        'objectproperty'            => ObjectPropertyHydrator::class,
        Reflection::class           => ReflectionHydrator::class,
        'reflectionhydrator'        => ReflectionHydrator::class,
        'reflection'                => ReflectionHydrator::class,
    ];

    /**
     * @var array<string, callable>
     */
    private $factories = [];

    /**
     * @var callable Invokable factory for hydrators without dedicated factories.
     */
    private $invokableFactory;

    public function __construct()
    {
        $this->invokableFactory = function (ContainerInterface $container, string $class) {
            return new $class();
        };

        $this->factories = [
            ArraySerializableHydrator::class => $this->invokableFactory,
            ClassMethodsHydrator::class      => $this->invokableFactory,
            DelegatingHydrator::class        => new DelegatingHydratorFactory(),
            ObjectPropertyHydrator::class    => $this->invokableFactory,
            ReflectionHydrator::class        => $this->invokableFactory,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function get($id)
    {
        $class = $this->resolveName($id);
        if (! $class) {
            throw Exception\MissingHydratorServiceException::forService($id);
        }

        $instance = ($this->factories[$class])($this, $class);

        return $instance;
    }

    /**
     * {@inheritDoc}
     */
    public function has($id)
    {
        return null !== $this->resolveName($id);
    }

    /**
     * Resolve a service name from an identifier.
     *
     * If $name is registered in $factories, the method returns it verbatim.
     *
     * Next it checks if the $name is registered verbatim in $aliases; if so,
     * it returns the target of the alias.
     *
     * finally, it does a strtolower() on it and looks to see if it exists
     * in the $aliases array; if so, it returns the target of the alias,
     * otherwise it returns null indicating inability to resolve.
     */
    private function resolveName(string $name) : ?string
    {
        if (isset($this->factories[$name])) {
            return $name;
        }

        if (isset($this->aliases[$name])) {
            return $this->aliases[$name];
        }

        $alias = strtolower($name);
        return $this->aliases[$alias] ?? null;
    }
}
