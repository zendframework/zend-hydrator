# MapNamingStrategy

`Zend\Hydrator\NamingStrategy\MapNamingStrategy` allows you to provide a map of
keys to use both during extraction and hydration; the map will translate the key
based on the direction:

- When a map is provided for hydration, but not extraction, the strategy will
  perform an `array_flip` on the hydration map when performing lookups.
  You can create an instance with this behavior using
  `MapNamingStrategy::createFromHydrationMap(array $hydrationMap) : MapNamingStrategy`.

- When a map is provided for extraction, but not hydration, the strategy will
  perform an `array_flip` on the extraction map when performing lookups.
  You can create an instance with this behavior using
  `MapNamingStrategy::createFromExtractionMap(array $extractionMap) : MapNamingStrategy`.

- When maps are provided for both extraction and hydration, the appropriate map
  will be used during extraction and hydration operations. You can create an
  instance with this behavior using
  `MapNamingStrategy::createFromAssymetricMap(array $extractionMap, array $hydrationStrategy) : MapNamingStrategy`.

Most of the time, you will want your maps symmetrical; as such, set either a
hydration map or an extraction map, but not both.

## Creating maps

### Hydration map only

```php
$namingStrategy = Zend\Hydrator\NamingStrategy\MapNamingStrategy::createFromHydrationMap(
    [
        'foo' => 'bar',
        'baz' => 'bash'
    ]
);
echo $namingStrategy->extract('bar'); // outputs: foo
echo $namingStrategy->extract('bash'); // outputs: baz

echo $namingStrategy->hydrate('foo'); // outputs: bar
echo $namingStrategy->hydrate('baz'); // outputs: bash
```

### Extraction map only

```php
$namingStrategy = Zend\Hydrator\NamingStrategy\MapNamingStrategy::createFromExtractionMap(
    null, // no hydration map
    [
        'foo' => 'bar',
        'baz' => 'bash'
    ]
);
echo $namingStrategy->extract('foo'); // outputs: bar
echo $namingStrategy->extract('baz'); // outputs: bash

echo $namingStrategy->hydrate('bar'); // outputs: foo
echo $namingStrategy->hydrate('bash'); // outputs: baz
```

### Both hydration and extraction maps

```php
$namingStrategy = Zend\Hydrator\NamingStrategy\MapNamingStrategy::createFromAssymetricMap(
    [
        'foo' => 'bar',
        'baz' => 'bash'
    ],
    [
        'is_bar'   => 'foo',
        'bashable' => 'baz',
    ]
);
echo $namingStrategy->extract('foo'); // outputs: bar
echo $namingStrategy->extract('baz'); // outputs: bash

echo $namingStrategy->hydrate('is_bar'); // outputs: foo
echo $namingStrategy->hydrate('bashable'); // outputs: baz
```

## Mapping keys for hydrators

This strategy can be used in hydrators to dictate how keys should be mapped:

```php
class Foo
{
    public $bar;
}

$namingStrategy = Zend\Hydrator\NamingStrategy\MapNamingStrategy::createFromHydrationMap([
    'foo' => 'bar',
    'baz' => 'bash',
]);
$hydrator = new Zend\Hydrator\ObjectPropertyHydrator();
$hydrator->setNamingStrategy($namingStrategy);

$foo = new Foo();
$hydrator->hydrate(['foo' => 123], $foo);

print_r($foo); // Foo Object ( [bar] => 123 )
print_r($hydrator->extract($foo)); // Array ( "foo" => 123 )
```
