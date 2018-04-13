# MapNamingStrategy

`Zend\Hydrator\NamingStrategy\MapNamingStrategy` allows you to provide a map of
keys to use when hydrating and extracting; the map will translate the key based
on the direction.

## Basic Usage

```php
$namingStrategy = new Zend\Hydrator\NamingStrategy\MapNamingStrategy(array(
    'foo' => 'bar',
    'baz' => 'bash'
));
echo $namingStrategy->hydrate('foo'); // outputs: bar
echo $namingStrategy->hydrate('baz'); // outputs: bash

echo $namingStrategy->extract('bar'); // outputs: foo
echo $namingStrategy->extract('bash'); // outputs: baz
```

This strategy can be used in hydrators to dictate how keys should be mapped:

```php
class Foo
{
    public $bar;
}

$namingStrategy = new Zend\Hydrator\NamingStrategy\MapNamingStrategy([
    'foo' => 'bar',
    'baz' => 'bash'
]);
$hydrator = new Zend\Hydrator\ObjectProperty();
$hydrator->setNamingStrategy($namingStrategy);

$foo = new Foo();
$hydrator->hydrate(['foo' => 123], $foo);

print_r($foo); // Foo Object ( [bar] => 123 )
print_r($hydrator->extract($foo)); // Array ( [foo] => 123 )
```
