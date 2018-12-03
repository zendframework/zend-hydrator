# Migration from version 2

This document details changes made between version 2 and version 3 that could
affect end-users.

## Minimum supported versions

### PHP

Version 3 bumps the minimum supported PHP version to version 7.2. We chose this
version in particular as it provides the `object` typehint, which allows us to
enforce at the engine level what we were previously enforcing only at the
documentation level.

Additionally, we have enabled strict types in all class files shipped with this
component.

### zend-eventmanager

The minimum supported version of zend-eventmanager (used by the
`AggregateHydrator`)is now 3.2.1.

### zend-serializer

The minimum supported version of zend-serializer (used by the
`SerializableStrategy`) is now 2.9.0.

### zend-servicemanager

The minimum supported version of zend-servicemanager (used by the
`HydratorPluginManager`) is now 3.3.2.

## Interface changes

Each of the interfaces provided by this package have been updated to add
typehints where they were previously omitted (due to engine limitations), both
on parameters and return values. These include:

- `Zend\Hydrator\ExtractionInterface`:
  - `extract($object)` becomes `extract(object $object) : array`
- `Zend\Hydrator\FilterEnabledInterface`:
  - `addFilter($name, $filter, $condition = Zend\Hydrator\Filter\FilterComposite::CONDITION_OR)` becomes `addFilter(string $name, $filter, int $condition = Zend\Hydrator\Filter\FilterComposite::CONDITION_OR) : void`
  - `hasFilter($name)` becomes `hasFilter(string $name) : bool`
  - `removeFilter($name)` becomes `removeFilter(string $name) : void`
- `Zend\Hydrator\HydrationInterface`:
  - `hydrate(array $data, $object)` becomes `hydrate(array $data, object $object) : object`
- `Zend\Hydrator\HydratorAwareInterface`:
  - `setHydrator(Zend\Hydrator\HydratorInterface $hydrator)` becomes `setHydrator(Zend\Hydrator\HydratorInterface $hydrator) : void`
  - `getHydrator()` becomes `getHydrator() : ?Zend\Hydrator\HydratorInterface`
  - `Zend\Hydrator\HydratorAwareTrait` was updated to follow the interface; if
    you use the trait to implement the interface, no changes will be necessary.
- `Zend\Hydrator\HydratorOptionsInterface`:
  - `setOptions($options)` becomes `setOptions(iterable $options) : void`
- `Zend\Hydrator\HydratorProviderInterface`:
  - `getHydratorConfig()` becomes `getHydratorConfig() : array`
- `Zend\Hydrator\NamingStrategyEnabledInterface`:
  - `setNamingStrategy(Zend\Hydrator\NamingStrategy\NamingStrategyInterface $strategy)` becomes `setNamingStrategy(Zend\Hydrator\NamingStrategy\NamingStrategyInterface $strategy) : void`
  - `getNamingStrategy()` becomes `getNamingStrategy() : Zend\Hydrator\NamingStrategy\NamingStrategyInterface`
  - `removeNamingStrategy()` becomes `removeNamingStrategy() : void`
- `Zend\Hydrator\StrategyEnabledInterface`:
  - `addStrategy($name, Zend\Hydrator\Strategy\StrategyInterface $strategy)` becomes `addStrategy(string $name, Zend\Hydrator\Strategy\StrategyInterface $strategy) : void`
  - `getStrategy($name)` becomes `getStrategy(string $name) : Zend\Hydrator\Strategy\StrategyInterface`
  - `hasStrategy($name)` becomes `hasStrategy(string $name) : bool`
  - `removeStrategy($name)` becomes `removeStrategy(string $name) : void`
- `Zend\Hydrator\Filter\FilterInterface`:
  - `filter($property)` becomes `filter(string $property) : bool`
- `Zend\Hydrator\Filter\FilterProviderInterface`:
  - `getFilter()` becomes `getFilter() : Zend\Hydrator\Filter\FilterInterface`
- `Zend\Hydrator\Iterator\HydratingIteratorInterface`:
  - `setPrototype($prototype)` becomes `setPrototype($prototype) : void` (`$prototype` continues to allow either a `string` or object)
  - `setHydrator(Zend\Hydrator\HydratorInterface $hydrator)` becomes `setHydrator(Zend\Hydrator\HydratorInterface $hydrator) : void`
- `Zend\Hydrator\NamingStrategy\NamingStrategyInterface`:
  - `extract($name)` becomes `extract(string $name, ?object $object = null) : string`
  - `hydrate($name)` becomes `hydrate(string $name, ?array $data = null) : string`
- `Zend\Hydrator\Strategy\StrategyInterface`:
  - `hydrate($value)` becomes `hydrate($value, ?array $data = null)` (the `$value` parameter and return value can be any PHP type)
  - `extract($value)` becomes `extract($value, ?object $object = null)` (the `$value` parameter and return value can be any PHP type)

All implementations of these interface shipped in the component have been
updated to ensure signatures match.

If you are providing custom implementations, or extending the implementations
provided in this package, you may need to update signatures per the above.

## Typehints

As noted in the above section, typehints were added to all interfaces. In
addition to those changes, the following methods were also updated to add
typehints:

- `Zend\Hydrator\Aggregate\AggregateHydrator`:
  - `add(Zend\Hydrator\HydratorInterface $hydrator, $priority = self::DEFAULT_PRIORITY)` becomes `add(Zend\Hydrator\HydratorInterface $hydrator, int $priority = self::DEFAULT_PRIORITY) : void`

- `Zend\Hydrator\Aggregate\ExtractEvent`:
  - `__construct($target, $extractionObject)` becomes `__construct(object $target, object $extractionObject)`
  - `getExtractionObject()` becomes `getExtractionObject() : object`
  - `setExtractionObject($extractionObject)` becomes `setExtractionObject(object $extractionObject) : void`
  - `getExtractedData()` becomes `getExtractedData() : array`
  - `setExtractedData(array $extractedData)` becomes `setExtractedData(array $extractedData) : void`
  - `mergeExtractedData(array $additionalData)` becomes `mergeExtractedData(array $additionalData) : void`

- `Zend\Hydrator\Aggregate\HydrateEvent`:
  - `__construct($target, $hydratedObject, array $hydrationData)` becomes `__construct(object $target, object $hydratedObject, array $hydrationData)`
  - `getHydratedObject()` becomes `getHydratedObject() : object`
  - `setHydratedObject($hydratedObject)` becomes `setHydratedObject(object $hydratedObject) : void`
  - `getHydrationData()` becomes `getHydrationData() : array`
  - `setHydrationData(array $hydrationData)` becomes `setHydrationData(array $hydrationData) : void`

- `Zend\Hydrator\Aggregate\HydratorListener`:
  - `onHydrate(HydrateEvent $event)` becomes `onHydrate(HydrateEvent $event) : object`
  - `onExtract(ExtractEvent $event)` becomes `onExtract(ExtractEvent $event) : array`

- `Zend\Hydrator\ClassMethods`:
  - `__construct($underscoreSeparatedKeys = true, $methodExistsCheck = false)` becomes `__construct(bool $underscoreSeparatedKeys = true, bool $methodExistsCheck = false)`
  - `setUnderscoreSeparatedKeys($underscoreSeparatedKeys)` becomes `setUnderscoreSeparatedKeys(bool $underscoreSeparatedKeys) : void`
  - `getUnderscoreSeparatedKeys()` becomes `getUnderscoreSeparatedKeys() : bool`
  - `setMethodExistsCheck($methodExistsCheck)` becomes `setMethodExistsCheck(bool $methodExistsCheck) : void`
  - `getMethodExistsCheck()` becomes `getMethodExistsCheck() : bool`

- `Zend\Hydrator\ConfigProvider`:
  - `__invoke()` becomes `__invoke() : array`
  - `getDependencyConfig()` becomes `getDependencyConfig() : array`

- `Zend\Hydrator\DelegatingHydratorFactory`:
  - no longer implements `Zend\ServiceManager\FactoryInterface`
  - `__invoke(Interop\Container\ContainerInterface $container, $requestedName, array $options = null)` becomes `__invoke(Psr\Container\ContainerInterface $container) : Zend\Hydrator\DelegatingHydrator`

- `Zend\Hydrator\Filter\FilterComposite`:
  - `__construct($orFilters = [], $andFilters = [])` becomes `__construct(array $orFilters = [], array $andFilters = [])`

- `Zend\Hydrator\Filter\MethodMatchFilter`:
  - `__construct($method, $exclude = true)` becomes `__construct(string $method, bool $exclude = true)`

- `Zend\Hydrator\Filter\NumberOfParameterFilter`:
  - `__construct($numberOfParameters = 0)` becomes `__construct(int $numberOfParameters = 0)`

- `Zend\Hydrator\HydratorPluginManagerFactory`:
  - no longer implements `Zend\ServiceManager\FactoryInterface`
  - `__invoke(Interop\Container\ContainerInterface $container, $requestedName, array $options = null)` becomes `__invoke(Psr\Container\ContainerInterface $container, string $name, array $options = []) : Zend\Hydrator\HydratorPluginManager`

- `Zend\Hydrator\Module`:
  - `getConfig()` becomes `getConfig() : array`
  - `init($moduleManager)` becomes `init(Zend\ModuleManager\ModuleManager $moduleManager) : void`

- `Zend\Hydrator\NamingStrategy\CompositeNamingStrategy`:
  - `__construct(array $strategies, Zend\Hydrator\NamingStrategy\NamingStrategyInterface $defaultNamingStrategy = null)` becomes `__construct(array $strategies, ?Zend\Hydrator\NamingStrategy\NamingStrategyInterface $defaultNamingStrategy = null)`

- `Zend\Hydrator\NamingStrategy\UnderscoreNamingStrategy\CamelCaseToUnderscoreFilter`:
  - `filter($value)` becomes `filter(string $value) : string`

- `Zend\Hydrator\NamingStrategy\UnderscoreNamingStrategy\UnderscoreToCamelCaseFilter`:
  - `filter($value)` becomes `filter(string $value) : string`

- `Zend\Hydrator\Strategy\ClosureStrategy`:
  - `__construct($extractFunc = null, $hydrateFunc = null)` becomes `__construct(?callable $extractFunc = null, ?callable $hydrateFunc = null)`

- `Zend\Hydrator\Strategy\CollectionStrategy`:
  - `__construct(Zend\Hydrator\HydratorInterface $objectHydrator, $objectClassName)` becomes `__construct(Zend\Hydrator\HydratorInterface $objectHydrator, string $objectClassName)`

- `Zend\Hydrator\Strategy\DateTimeFormatterStrategy`:
  - `__construct($format = DateTime::RFC3339, DateTimeZone $timezone = null, $dateTimeFallback = false)` becomes `__construct(string $format = DateTime::RFC3339, ?DateTimeZone $timezone = null, bool $dateTimeFallback = false)`

- `Zend\Hydrator\Strategy\ExplodeStrategy`:
  - `__construct($delimiter = ',', $explodeLimit = null)` becomes `__construct(string $delimiter = ',', ?int $explodeLimit = null)`

- `Zend\Hydrator\Strategy\SerializableStrategy`:
  - `__construct($serializer, $serializerOptions = null)` becomes `__construct($serializer, ?iterable $serializerOptions = null)`
  - `setSerializer($serializer)` becomes `setSerializer($serializer) : void`
  - `getSerializer()` becomes `getSerializer($serializer) : Zend\Serializer\Adapter\AdapterInterface`
  - `setSerializerOptions($serializerOptions)` becomes `setSerializerOptions(iterable $serializerOptions) : void`
  - `getSerializerOptions()` becomes `getSerializerOptions() : array`

- `Zend\Hydrator\Strategy\StrategyChain`:
  - `__construct($extractionStrategies)` becomes `__construct(iterable $extractionStrategies)`

## ArrayMapNamingStrategy and MapNamingStrategy merged

`ArrayMapNamingStrategy` and `MapNamingStrategy` were performing essentially the
same duties, but in reverse. As such, for version 3, we have merged the two into
`MapNamingStrategy`. To accommodate the three different use cases, we provide
three "named constructors":

```php
public static function createFromExtractionMap(array $extractionMap) : MapNamingStrategy;
public static function createFromHydrationMap(array $hydrationMap) : MapNamingStrategy;
public static function createFromAssymetricMap(array $extractionMap, array $hydrationMap) : MapNamingStrategy;
```

In the first two cases, the constructor will flip the arrays for purposes of the
opposite interaction; e.g., using `createFromExtractionMap()` will create a
hydration map based on an `array_flip()` of the extraction map provided.

**You MUST use one of these methods to create an instance,** as the constructor
is now marked `private`.

## HydratorPluginManager

This version removes support for zend-servicemanager v2 service names. Under
zend-servicemanager v2, most special characters were removed, and the name
normalized to all lowercase. Now, only fully qualified class names are mapped to
factories, and short names (names omitting the namespace) are mapped as aliases.
