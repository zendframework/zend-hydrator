# Zend\\Hydrator\\Strategy

You can add `Zend\Hydrator\Strategy\StrategyInterface` to any of the hydrators
(except if it extends `Zend\Hydrator\AbstractHydrator` or implements
`Zend\Hydrator\HydratorInterface` and `Zend\Hydrator\Strategy\StrategyEnabledInterface`)
to manipulate the way how they behave on `extract()` and `hydrate()` for
specific key / value pairs. This is the interface that needs to be implemented:

```php
namespace Zend\Hydrator\Strategy;

interface StrategyInterface
{
     /**
      * Converts the given value so that it can be extracted by the hydrator.
      *
      * @param mixed $value The original value.
      * @return mixed Returns the value that should be extracted.
      */
     public function extract($value);

     /**
      * Converts the given value so that it can be hydrated by the hydrator.
      *
      * @param mixed $value The original value.
      * @return mixed Returns the value that should be hydrated.
      */
     public function hydrate($value);
}
```

This interface is similar to `Zend\Hydrator\HydratorInterface`; the reason
is that strategies provide a proxy implementation for `hydrate()` and `extract()`.

## Adding strategies to the hydrators

To allow strategies within your hydrator, `Zend\Hydrator\Strategy\StrategyEnabledInterface`
provides the following methods:

```php
namespace Zend\Hydrator;

use Zend\Hydrator\Strategy\StrategyInterface;

interface StrategyEnabledInterface
{
    /**
     * Adds the given strategy under the given name.
     *
     * @param string $name The name of the strategy to register.
     * @param StrategyInterface $strategy The strategy to register.
     * @return HydratorInterface
     */
    public function addStrategy($name, StrategyInterface $strategy);

    /**
     * Gets the strategy with the given name.
     *
     * @param string $name The name of the strategy to get.
     * @return StrategyInterface
     */
    public function getStrategy($name);

    /**
     * Checks if the strategy with the given name exists.
     *
     * @param string $name The name of the strategy to check for.
     * @return bool
     */
    public function hasStrategy($name);

    /**
     * Removes the strategy with the given name.
     *
     * @param string $name The name of the strategy to remove.
     * @return HydratorInterface
     */
    public function removeStrategy($name);
}
```

Every hydrator shipped by default provides this functionality;
`AbstractHydrator` fully implements it as well. As such, if you want to use this
functionality in your own hydrators, you should extend `AbstractHydrator`.

## Available implementations

### Zend\\Hydrator\\Strategy\\BooleanStrategy

This Strategy converts values into Booleans and vice versa. It expects two arguments at the constructor, which are used to define a contrary for `true` and `false`.

### Zend\\Hydrator\\Strategy\\DateTimeFormatterStrategy 

This is a strategy that converts bidirectional a string into a DateTime Object. 
The in- and output format can be defined as a constructor argument.

### Zend\\Hydrator\\Strategy\\ExplodeStrategy 

This Strategy is a wrapper around PHPs `implode` and `explode` functions. The delemiter 
and a limit can be defined at teh constructor. The Limit is only respected in the `extract`
function.

### Zend\\Hydrator\\Strategy\\SerializableStrategy

This is a strategy that provides the functionality for
`Zend\Hydrator\ArraySerializable`.  You can use it with custom implementations
for `Zend\Serializer\Adapter\AdapterInterface` if you want to as well.

### Zend\\Hydrator\\Strategy\\ClosureStrategy

This is a strategy that allows you to pass in a `hydrate` callback to be called
in the event of hydration, and an `extract` callback to be called in the event
of extraction.

### Zend\\Hydrator\\Strategy\\DefaultStrategy

This is a "dummy"-implementation that simply proxies everything through, without
doing anything on the parameters.

## Writing custom strategies

As usual, this is not really a very useful example, but will provide you with
the basics to start with writing your own strategies, as well as provide ideas
as to where and when to use them. This strategy simply transform the value for
the defined key using `rot13` during `extract()` and `hydrate()`:

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

$hydrator = new ClassMethods();
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
