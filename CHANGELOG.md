# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## 3.0.0 - TBD

### Added

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [#14](https://github.com/zendframework/zend-hydrator/pull/14) replaced usage of zend filters w/ hardcoded versions
- The following visibility changes occurred to `\Zend\Hydrator\NamingStrategy\UnderscoreNamingStrategy`:
  - static property `$underscoreToStudlyCaseFilter` was renamed to `$underscoreToCamelCaseFilter` and marked `private`
  - static property `$camelCaseToUnderscoreFilter` was marked `private`
  - method `getCamelCaseToUnderscoreFilter` was marked `private`
  - method `getUnderscoreToStudlyCaseFilter` was renamed to `getUnderscoreToCamelCaseFilter` and marked `private`

## 2.0.1 - TBD

### Added

- The following classes were marked `final`:
  - `\Zend\Hydrator\NamingStrategy\UnderscoreNamingStrategy\UnderscoreToCamelCaseFilter`
  - `\Zend\Hydrator\NamingStrategy\UnderscoreNamingStrategy\CamelCaseToUnderscoreFilter`

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [#6](https://github.com/zendframework/zend-hydrator/pull/6) add additional
  unit test coverage

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
