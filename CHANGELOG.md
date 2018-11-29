# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## 3.0.0 - TBD

### Added

- [#79](https://github.com/zendframework/zend-hydrator/pull/79) adds a third, optional parameter to the `DateTimeFormatterStrategy` constructor.
  The parameter is a boolean, and, when enabled, a string that can be parsed by
  the `DateTime` constructor will still result in a `DateTime` instance during
  hydration, even if the string does not follow the provided date-time format.

- [#14](https://github.com/zendframework/zend-hydrator/pull/14) adds the following `final` classes:
  - `\Zend\Hydrator\NamingStrategy\UnderscoreNamingStrategy\UnderscoreToCamelCaseFilter`
  - `\Zend\Hydrator\NamingStrategy\UnderscoreNamingStrategy\CamelCaseToUnderscoreFilter`

### Changed

- [#80](https://github.com/zendframework/zend-hydrator/pull/80) bumps the minimum supported PHP version to 7.2.

- [#80](https://github.com/zendframework/zend-hydrator/pull/80) bumps the minimum supported zend-eventmanager version to 3.2.1. zend-eventmanager
  is only required if you are using the `AggregateHydrator`.

- [#80](https://github.com/zendframework/zend-hydrator/pull/80) bumps the minimum supported zend-serializer version to 2.9.0. zend-serializer is
  only required if you are using the `SerializableStrategy`.

- [#80](https://github.com/zendframework/zend-hydrator/pull/80) bumps the minimum supported zend-servicemanager version to 3.3.2.
  zend-servicemanager is only required if you are using the
  `HydratorPluginManager` or `DelegatingHydrator`. This change means that
  some service names supported by zend-servicemanager v2 will no longer work.
  When in doubt, use the fully qualified class name, or the class name minus the
  namespace, with correct casing.

- [#80](https://github.com/zendframework/zend-hydrator/pull/80) adds scalar and object typehints, both to parameters and return values, wherever
  possible. For consumers, this should pose no discernable change. **For those
  implementing interfaces or extending classes from this package, updates will
  be necessary to ensure your code will run.** [See the migration guide for
  details](https://docs.zendframework.com/zend-hydrator/v3/migration/).

- [#14](https://github.com/zendframework/zend-hydrator/pull/14) replaces usage of zend-filter with the hardcoded filters referenced in
  the above section.

- [#14](https://github.com/zendframework/zend-hydrator/pull/14) made the following visibility changes to `\Zend\Hydrator\NamingStrategy\UnderscoreNamingStrategy`:
  - static property `$underscoreToStudlyCaseFilter` was renamed to `$underscoreToCamelCaseFilter` and marked `private`
  - static property `$camelCaseToUnderscoreFilter` was marked `private`
  - method `getCamelCaseToUnderscoreFilter` was marked `private`
  - method `getUnderscoreToStudlyCaseFilter` was renamed to `getUnderscoreToCamelCaseFilter` and marked `private`

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.

## 2.4.1 - 2018-11-19

### Added

- Nothing.

### Changed

- [#69](https://github.com/zendframework/zend-hydrator/pull/69) adds support for special pre/post characters in formats passed to the
  `DateTimeFormatterStrategy`. When used, the `DateTime` instances created
  during hydration will (generally) omit the time element, allowing for more
  accurate comparisons.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.

## 2.4.0 - 2018-04-30

### Added

- [#70](https://github.com/zendframework/zend-hydrator/pull/70) updates the `DateTimeFormatterStrategy` to work with any `DateTimeInterface`,
  and not just `DateTime`.

### Changed

- [#75](https://github.com/zendframework/zend-hydrator/pull/75) ensures continuous integration _requires_ PHP 7.2 tests to pass;
  they already were.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.

## 2.3.1 - 2017-10-02

### Added

- Nothing.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [#67](https://github.com/zendframework/zend-hydrator/pull/67) fixes an issue
  in the `ArraySerializable::hydrate()` logic whereby nested array data was
  _merged_ instead of _replaced_ during hydration. The hydrator now correctly
  replaces such data.

## 2.3.0 - 2017-09-20

### Added

- [#27](https://github.com/zendframework/zend-hydrator/pull/27) adds the
  interface `Zend\Hydrator\HydratorProviderInterface` for use with the
  zend-modulemanager `ServiceListener` implementation, and updates the
  `HydratorManager` definition for the `ServiceListener` to typehint on this new
  interface instead of the one provided in zend-modulemanager.

  Users implementing the zend-modulemanager `Zend\ModuleManger\Feature\HydratorProviderInterface`
  will be unaffected, as the method it defines, `getHydratorConfig()`, will
  still be identified and used to inject he `HydratorPluginManager`. However, we
  recommend updating your `Module` classes to use the new interface instead.

- [#44](https://github.com/zendframework/zend-hydrator/pull/44) adds
  `Zend\Hydrator\Strategy\CollectionStrategy`. This class allows you to provide
  a single hydrator to use with an array of objects or data that represent the
  same type.

  From the patch, if the "users" property of an object you will hydrate is
  expected to be an array of items of a type `User`, you could do the following:

  ```php
  $hydrator->addStrategy('users', new CollectionStrategy(
      new ReflectionHydrator(),
      User::class
  ));
  ```

- [#63](https://github.com/zendframework/zend-hydrator/pull/63) adds support for
  PHP 7.2.

### Changed

- [#44](https://github.com/zendframework/zend-hydrator/pull/44) updates the
  `ClassMethods` hydrator to add a second, optional, boolean argument to the
  constructor, `$methodExistsCheck`, and a related method
  `setMethodExistsCheck()`. These allow you to specify a flag indicating whether
  or not the name of a property must directly map to a _defined_ method, versus
  one that may be called via `__call()`. The default value of the flag is
  `false`, which retains the previous behavior of not checking if the method is
  defined. Set the flag to `true` to make the check more strict.

### Deprecated

- Nothing.

### Removed

- [#63](https://github.com/zendframework/zend-hydrator/pull/63) removes support for HHVM.

### Fixed

- Nothing.

## 2.2.3 - 2017-09-20

### Added

- Nothing.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [#65](https://github.com/zendframework/zend-hydrator/pull/65) fixes the
  hydration behavior of the `ArraySerializable` hydrator when using
  `exchangeArray()`. Previously, the method would clear any existing values from
  the instance, which is problematic when a partial update is provided as values
  not in the update would disappear. The class now pulls the original values,
  and recursively merges the replacement with those values.

## 2.2.2 - 2017-05-17

### Added

### Changes

- [#42](https://github.com/zendframework/zend-hydrator/pull/42) updates the
  `ConfigProvider::getDependencies()` method to map the `HydratorPluginManager`
  class to the `HydratorPluginManagerFactory` class, and make the
  `HydratorManager` service an alias to the fully-qualified
  `HydratorPluginManager` class.
- [#45](https://github.com/zendframework/zend-hydrator/pull/45) changes the
  `ClassMethods` hydrator to take into account naming strategies when present,
  making it act consistently with the other hydrators.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [#59](https://github.com/zendframework/zend-hydrator/pull/59) fixes how the
  `HydratorPluginManagerFactory` factory initializes the plugin manager
  instance, ensuring it is injecting the relevant configuration from the
  `config` service and thus seeding it with configured hydrator services. This
  means that the `hydrators` configuration will now be honored in non-zend-mvc
  contexts.

## 2.2.1 - 2016-04-18

### Added

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [#28](https://github.com/zendframework/zend-hydrator/pull/28) fixes the
  `Module::init()` method to properly receive a `ModuleManager` instance, and
  not expect a `ModuleEvent`.

## 2.2.0 - 2016-04-06

### Added

- [#26](https://github.com/zendframework/zend-hydrator/pull/26) exposes the
  package as a ZF component and/or generic configuration provider, by adding the
  following:
  - `HydratorPluginManagerFactory`, which can be consumed by container-interop /
    zend-servicemanager to create and return a `HydratorPluginManager` instance.
  - `ConfigProvider`, which maps the service `HydratorManager` to the above
    factory.
  - `Module`, which does the same as `ConfigProvider`, but specifically for
    zend-mvc applications. It also provices a specification to
    `Zend\ModuleManager\Listener\ServiceListener` to allow modules to provide
    hydrator configuration.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.

## 2.1.0 - 2016-02-18

### Added

- [#20](https://github.com/zendframework/zend-hydrator/pull/20) imports the
  documentation from zend-stdlib, publishes it to
  https://zendframework.github.io/zend-hydrator/, and automates building and
  publishing the documentation.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [#6](https://github.com/zendframework/zend-hydrator/pull/6) add additional
  unit test coverage
- [#17](https://github.com/zendframework/zend-hydrator/pull/17) and
  [#23](https://github.com/zendframework/zend-hydrator/pull/23) update the code
  to be forwards compatible with zend-servicemanager v3, and to depend on
  zend-stdlib and zend-eventmanager v3.

## 2.0.0 - 2015-09-17

### Added

- The following classes were marked `final` (per their original implementation
  in zend-stdlib):
  - `Zend\Hydrator\NamingStrategy\IdentityNamingStrategy`
  - `Zend\Hydrator\NamingStrategy\ArrayMapNamingStrategy`
  - `Zend\Hydrator\NamingStrategy\CompositeNamingStrategy`
  - `Zend\Hydrator\Strategy\ExplodeStrategy`
  - `Zend\Hydrator\Strategy\StrategyChain`
  - `Zend\Hydrator\Strategy\DateTimeFormatterStrategy`
  - `Zend\Hydrator\Strategy\BooleanStrategy`

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.

## 1.0.0 - 2015-09-17

Initial release. This ports all hydrator classes and functionality from
[zend-stdlib](https://github.com/zendframework/zend-stdlib) to a standalone
repository. All final keywords are removed, to allow a deprecation cycle in the
zend-stdlib component.

Please note: the following classes will be marked as `final` for a version 2.0.0
release to immediately follow 1.0.0:

- `Zend\Hydrator\NamingStrategy\IdentityNamingStrategy`
- `Zend\Hydrator\NamingStrategy\ArrayMapNamingStrategy`
- `Zend\Hydrator\NamingStrategy\CompositeNamingStrategy`
- `Zend\Hydrator\Strategy\ExplodeStrategy`
- `Zend\Hydrator\Strategy\StrategyChain`
- `Zend\Hydrator\Strategy\DateTimeFormatterStrategy`
- `Zend\Hydrator\Strategy\BooleanStrategy`

As such, you should not extend them.

### Added

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.
