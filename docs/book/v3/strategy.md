# Zend\\Hydrator\\Strategy

You can compose `Zend\Hydrator\Strategy\StrategyInterface` instances in any of
the hydrators to manipulate the way they behave on `extract()` and `hydrate()`
for specific key/value pairs. The interface offers the following definitions:

```php
namespace Zend\Hydrator\Strategy;

interface StrategyInterface
{
    /**
     * Converts the given value so that it can be extracted by the hydrator.
     *
     * @param  mixed       $value The original value.
     * @param  null|object $object (optional) The original object for context.
     * @return mixed       Returns the value that should be extracted.
     */
    public function extract($value, ?object $object = null);

    /**
     * Converts the given value so that it can be hydrated by the hydrator.
     *
     * @param  mixed      $value The original value.
     * @param  null|array $data (optional) The original data for context.
     * @return mixed      Returns the value that should be hydrated.
     */
    public function hydrate($value, ?array $data = null);
}
```

This interface is similar to what the `Zend\Hydrator\ExtractionInterface` and
`Zend\Hydrator\HydrationInterface` provide; the reason is that strategies
provide a proxy implementation for `hydrate()` and `extract()` on individual
values. For this reason, their return types are listed as mixed, versus as
`array` and `object`, respectively.

## Adding strategies to the hydrators

This package provides the interface `Zend\Hydrator\Strategy\StrategyEnabledInterface`.
Hydrators can implement this interface, and then call on its `getStrategy()`
method in order to extract or hydrate individual values. The interface has the
following definition:

```php
namespace Zend\Hydrator\Strategy;

interface StrategyEnabledInterface
{
    /**
     * Adds the given strategy under the given name.
     */
    public function addStrategy(string $name, StrategyInterface $strategy) : void;

    /**
     * Gets the strategy with the given name.
     */
    public function getStrategy(string $name) : StrategyInterface;

    /**
     * Checks if the strategy with the given name exists.
     */
    public function hasStrategy(string $name) : bool;

    /**
     * Removes the strategy with the given name.
     */
    public function removeStrategy(string $name) : void;
}
```

We provide a default implementation of the interface as part of
`Zend\Hydrator\AbstractHydrator`; it uses an array property to store and
retrieve strategies by name when extracting and hydrating values. Since all
shipped hydrators are based on `AbstractHydrator`, they share these
capabilities.

Additionally, the functionality that consumes strategies within
`AbstractHydrator` also contains checks if a naming strategy is composed, and,
if present, will use it to translate the property name prior to looking up a
  strategy for it.

## Available implementations

### Zend\\Hydrator\\Strategy\\BooleanStrategy

This strategy converts values into Booleans and vice versa. It expects two
arguments at the constructor, which are used to define value maps for `true` and
`false`.

### Zend\\Hydrator\\Strategy\\ClosureStrategy

This is a strategy that allows you to pass in options for:

- `hydrate`, a callback to be called when hydrating a value, and
- `extract`, a callback to be called when extracting a value.

### Zend\\Hydrator\\Strategy\\DateTimeFormatterStrategy

`DateTimeFormatterStrategy` provides bidirectional conversion between strings
and DateTime instances. The input and output formats can be provided as
constructor arguments.

The strategy allows `DateTime` formats that use `!` to prepend the format, or
`|` or `+` to append it; these ensure that, during hydration, the new `DateTime`
instance created will set the time element accordingly. As a specific example,
`Y-m-d|` will drop the time component, ensuring comparisons are based on a
midnight time value.

### Zend\\Hydrator\\Strategy\\DefaultStrategy

The `DefaultStrategy` simply proxies everything through, without performing any
conversion of values.

### Zend\\Hydrator\\Strategy\\ExplodeStrategy

This strategy is a wrapper around PHP's `implode()` and `explode()` functions.
The delimiter and a limit can be provided to the constructor; the limit will
only be used for `extract` operations.

### Zend\\Hydrator\\Strategy\\SerializableStrategy

`SerializableStrategy` provides the functionality backing
`Zend\Hydrator\ArraySerializableHydrator`. You can use it with custom
implementations for `Zend\Serializer\Adapter\AdapterInterface` if you want to as
well.

### Zend\\Hydrator\\Strategy\\StrategyChain

This strategy takes an array of `StrategyInterface` instances and iterates
over them when performing `extract()` and `hydrate()` operations. Each operates
on the return value of the previous, allowing complex operations based on
smaller, single-purpose strategies.

## Writing custom strategies

The following example, while not terribly useful, will provide you with the
basics for writing your own strategies, as well as provide ideas as to where and
when to use them. This strategy simply transforms the value for the defined key
using `str_rot13()` during both the `extract()` and `hydrate()` operations:

```php
class Rot13Strategy implements StrategyInterface
{
    public function extract($value)
    {
        return str_rot13($value);
    }

    public function hydrate($value)
    {
        return str_rot13($value);
    }
}
```

This is the example class with which we want to use the hydrator example:

```php
class Foo
{
    protected $foo = null;
    protected $bar = null;

    public function getFoo()
    {
        return $this->foo;
    }

    public function setFoo($foo)
    {
        $this->foo = $foo;
    }

    public function getBar()
    {
        return $this->bar;
    }

    public function setBar($bar)
    {
        $this->bar = $bar;
    }
}
```

Now, we'll add the `rot13` strategy to the method `getFoo()` and `setFoo($foo)`:

```php
$foo = new Foo();
$foo->setFoo('bar');
$foo->setBar('foo');

$hydrator = new ClassMethodsHydrator();
$hydrator->addStrategy('foo', new Rot13Strategy());
```

When you use the hydrator to extract an array for the object `$foo`, you'll
receive the following:

```php
$extractedArray = $hydrator->extract($foo);

// array(2) {
//     ["foo"]=>
//     string(3) "one"
//     ["bar"]=>
//     string(3) "foo"
// }
```

And when hydrating a new `Foo` instance:

```php
$hydrator->hydrate($extractedArray, $foo)

// object(Foo)#2 (2) {
//   ["foo":protected]=>
//   string(3) "bar"
//   ["bar":protected]=>
//   string(3) "foo"
// }
```
