<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Zend\Hydrator;

use Traversable;
use Zend\Stdlib\ArrayUtils;

class ClassMethods extends AbstractHydrator implements HydratorOptionsInterface
{
    /**
     * Flag defining whether array keys are underscore-separated (true) or camel case (false)
     *
     * @var bool
     */
    protected $underscoreSeparatedKeys = true;

    /**
     * Flag defining whether to check the setter method with method_exists to prevent the
     * hydrator from calling __call during hydration
     *
     * @var bool
     */
    protected $methodExistsCheck = false;

    /**
     * Holds the names of the methods used for hydration, indexed by class::property name,
     * false if the hydration method is not callable/usable for hydration purposes
     *
     * @var string[]|bool[]
     */
    private $hydrationMethodsCache = [];

    /**
     * A map of extraction methods to property name to be used during extraction, indexed
     * by class name and method name
     *
     * @var null[]|string[][]
     */
    private $extractionMethodsCache = [];

    /**
     * @var Filter\FilterInterface
     */
    private $callableMethodFilter;

    /**
     * Define if extract values will use camel case or name with underscore
     */
    public function __construct(bool $underscoreSeparatedKeys = true, bool $methodExistsCheck = false)
    {
        parent::__construct();

        $this->setUnderscoreSeparatedKeys($underscoreSeparatedKeys);
        $this->setMethodExistsCheck($methodExistsCheck);

        $this->callableMethodFilter = new Filter\OptionalParametersFilter();

        $this->filterComposite->addFilter('is', new Filter\IsFilter());
        $this->filterComposite->addFilter('has', new Filter\HasFilter());
        $this->filterComposite->addFilter('get', new Filter\GetFilter());
        $this->filterComposite->addFilter(
            'parameter',
            new Filter\OptionalParametersFilter(),
            Filter\FilterComposite::CONDITION_AND
        );
    }

    /**
     * @param mixed[] $options
     */
    public function setOptions(iterable $options) : void
    {
        if ($options instanceof Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        }

        if (isset($options['underscoreSeparatedKeys'])) {
            $this->setUnderscoreSeparatedKeys($options['underscoreSeparatedKeys']);
        }

        if (isset($options['methodExistsCheck'])) {
            $this->setMethodExistsCheck($options['methodExistsCheck']);
        }
    }

    public function setUnderscoreSeparatedKeys(bool $underscoreSeparatedKeys) : void
    {
        $this->underscoreSeparatedKeys = $underscoreSeparatedKeys;

        if ($this->underscoreSeparatedKeys) {
            $this->setNamingStrategy(new NamingStrategy\UnderscoreNamingStrategy());
            return;
        }

        if ($this->hasNamingStrategy()) {
            $this->removeNamingStrategy();
            return;
        }
    }

    public function getUnderscoreSeparatedKeys() : bool
    {
        return $this->underscoreSeparatedKeys;
    }

    public function setMethodExistsCheck(bool $methodExistsCheck) : void
    {
        $this->methodExistsCheck = $methodExistsCheck;
    }

    public function getMethodExistsCheck() : bool
    {
        return $this->methodExistsCheck;
    }

    /**
     * Extract values from an object with class methods
     *
     * Extracts the getter/setter of the given $object.
     *
     * {@inheritDoc}
     */
    public function extract(object $object) : array
    {
        $objectClass = get_class($object);

        // reset the hydrator's hydrator's cache for this object, as the filter may be per-instance
        if ($object instanceof Filter\FilterProviderInterface) {
            $this->extractionMethodsCache[$objectClass] = null;
        }

        // pass 1 - finding out which properties can be extracted, with which methods (populate hydration cache)
        if (! isset($this->extractionMethodsCache[$objectClass])) {
            $this->extractionMethodsCache[$objectClass] = [];
            $filter                                     = $this->filterComposite;
            $methods                                    = get_class_methods($object);

            if ($object instanceof Filter\FilterProviderInterface) {
                $filter = new Filter\FilterComposite(
                    [$object->getFilter()],
                    [new Filter\MethodMatchFilter('getFilter')]
                );
            }

            foreach ($methods as $method) {
                $methodFqn = $objectClass . '::' . $method;

                if (! ($filter->filter($methodFqn) && $this->callableMethodFilter->filter($methodFqn))) {
                    continue;
                }

                $attribute = $method;

                if (strpos($method, 'get') === 0) {
                    $attribute = substr($method, 3);
                    if (! property_exists($object, $attribute)) {
                        $attribute = lcfirst($attribute);
                    }
                }

                $this->extractionMethodsCache[$objectClass][$method] = $attribute;
            }
        }

        $values = [];

        if (null === $this->extractionMethodsCache[$objectClass]) {
            return $values;
        }

        // pass 2 - actually extract data
        foreach ($this->extractionMethodsCache[$objectClass] as $methodName => $attributeName) {
            $realAttributeName          = $this->extractName($attributeName, $object);
            $values[$realAttributeName] = $this->extractValue($realAttributeName, $object->$methodName(), $object);
        }

        return $values;
    }

    /**
     * Hydrate an object by populating getter/setter methods
     *
     * Hydrates an object by getter/setter methods of the object.
     *
     * {@inheritDoc}
     */
    public function hydrate(array $data, object $object) : object
    {
        $objectClass = get_class($object);

        foreach ($data as $property => $value) {
            $propertyFqn = $objectClass . '::$' . $property;

            if (! isset($this->hydrationMethodsCache[$propertyFqn])) {
                $setterName = 'set' . ucfirst($this->hydrateName($property, $data));

                $this->hydrationMethodsCache[$propertyFqn] = is_callable([$object, $setterName])
                    && (! $this->methodExistsCheck || method_exists($object, $setterName))
                    ? $setterName
                    : false;
            }

            if ($this->hydrationMethodsCache[$propertyFqn]) {
                $object->{$this->hydrationMethodsCache[$propertyFqn]}($this->hydrateValue($property, $value, $data));
            }
        }

        return $object;
    }

    /**
     * {@inheritDoc}
     */
    public function addFilter(string $name, $filter, int $condition = Filter\FilterComposite::CONDITION_OR) : void
    {
        $this->resetCaches();
        parent::addFilter($name, $filter, $condition);
    }

    /**
     * {@inheritDoc}
     */
    public function removeFilter(string $name) : void
    {
        $this->resetCaches();
        parent::removeFilter($name);
    }

    /**
     * {@inheritDoc}
     */
    public function setNamingStrategy(NamingStrategy\NamingStrategyInterface $strategy) : void
    {
        $this->resetCaches();
        parent::setNamingStrategy($strategy);
    }

    /**
     * {@inheritDoc}
     */
    public function removeNamingStrategy() : void
    {
        $this->resetCaches();
        parent::removeNamingStrategy();
    }

    /**
     * Reset all local hydration/extraction caches
     */
    private function resetCaches() : void
    {
        $this->hydrationMethodsCache = $this->extractionMethodsCache = [];
    }
}
