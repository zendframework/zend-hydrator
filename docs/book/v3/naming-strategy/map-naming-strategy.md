# MapNamingStrategy

`Zend\Hydrator\NamingStrategy\MapNamingStrategy` allows you to provide a
maps of keys to use both when extraction and hydrating; the map will translate
the key based on the direction:

- When a map is provided for hydration, but not extraction, the strategy will
  perform an `array_flip` on the hydration map when performing lookups.

- When a map is provided for extraction, but not hydration, the strategy will
  perform an `array_flip` on the extraction map when performing lookups.

- When maps are provided for both extraction and hydration, the appropriate map
  will be used during extraction and hydration operations.

Most of the time, you will want your maps symmetrical; as such, set either a
hydration map or an extraction map, but not both. Since the hydration map is the
first argument, that is the one you should generally define.

## Creating maps

### Hydration map only

```php
$namingStrategy = new Zend\Hydrator\NamingStrategy\MapNamingStrategy(
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
$namingStrategy = new Zend\Hydrator\NamingStrategy\MapNamingStrategy(
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
$namingStrategy = new Zend\Hydrator\NamingStrategy\MapNamingStrategy(
    [
        'is_bar'   => 'foo',
        'bashable' => 'baz',
    ],
    [
        'foo' => 'bar',
        'baz' => 'bash'
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

$namingStrategy = new Zend\Hydrator\NamingStrategy\MapNamingStrategy([
    'foo' => 'bar',
    'baz' => 'bash',
]);
$hydrator = new Zend\Hydrator\ObjectProperty();
$hydrator->setNamingStrategy($namingStrategy);

$foo = new Foo();
$hydrator->hydrate(['foo' => 123], $foo);

print_r($foo); // Foo Object ( [bar] => 123 )
print_r($hydrator->extract($foo)); // Array ( "foo" => 123 )
```
