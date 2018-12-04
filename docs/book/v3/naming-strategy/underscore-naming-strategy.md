# UnderscoreNamingStrategy

`Zend\Hydrator\NamingStrategy\UnderscoreNamingStrategy` converts snake case strings (e.g.
`foo_bar_baz`) to camel-case strings (e.g. `fooBarBaz`) and vice versa.

## Basic Usage

```php
$namingStrategy = new Zend\Hydrator\NamingStrategy\UnderscoreNamingStrategy();

echo $namingStrategy->extract('foo_bar'); // outputs: foo_bar
echo $namingStrategy->extract('Foo_Bar'); // outputs: foo_bar
echo $namingStrategy->extract('FooBar'); // outputs: foo_bar

echo $namingStrategy->hydrate('fooBar'); // outputs: fooBar
echo $namingStrategy->hydrate('FooBar'); // outputs: fooBar
echo $namingStrategy->hydrate('Foo_Bar'); // outputs: fooBar
```

This strategy can be used in hydrators to dictate how keys should be mapped.

```php
class Foo
{
    public $fooBar;
}

$hydrator = new Zend\Hydrator\ObjectPropertyHydrator();
$hydrator->setNamingStrategy(new Zend\Hydrator\NamingStrategy\UnderscoreNamingStrategy());

$foo = new Foo();
$hydrator->hydrate(['foo_bar' => 123], $foo);

print_r($foo); // Foo Object ( [fooBar] => 123 )
print_r($hydrator->extract($foo)); // Array ( [foo_bar] => 123 )
```
