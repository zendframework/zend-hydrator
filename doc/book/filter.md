# Zend\\Hydrator\\Filter

Hydrator filters allow you to manipulate the behavior of the `extract()`
operation.  This is especially useful, if you want to omit some internals (e.g.
`getServiceManager()`) from the array representation.

It comes with a helpful `Composite` implementation, and several filters for
common use cases. The filters are composed in the `AbstractHydrator`, so you can
start using them immediately in any custom extensions you write that extend that
class.

```php
namespace Zend\Hydrator\Filter;

interface FilterInterface
{
    /**
     * Should return true, if the given filter
     * does not match
     *
     * @param string $property The name of the property
     * @return bool
     */
    public function filter($property);
}
```

If it returns true, the key/value pairs will be in the extracted arrays - if it
returns false, you'll not see them again.

## Filter implementations

### Zend\\Hydrator\\Filter\\GetFilter

This filter is used in the `ClassMethods` hydrator to decide which getters will
be extracted. It checks if the key to extract starts with `get` or the object
contains a method beginning with `get` (e.g., `Zend\Foo\Bar::getFoo`).

### Zend\\Hydrator\\Filter\\HasFilter

This filter is used in the `ClassMethods` hydrator to decide which `has` methods
will be extracted. It checks if the key to extract begins with `has` or the
object contains a method beginning with `has` (e.g., `Zend\Foo\Bar::hasFoo`).

### Zend\\Hydrator\\Filter\\IsFilter

This filter is used in the `ClassMethods` hydrator to decide which `is` methods
will be extracted. It checks if the key to extract begins with `is` or the
object contains a method beginning with `is` (e.g., `Zend\Foo\Bar::isFoo`).

### Zend\\Hydrator\\Filter\\MethodMatchFilter

This filter allows you to omit methods during extraction that match the
condition defined in the composite.  The name of the method is specified in the
constructor of this filter; the second parameter decides whether to use white or
blacklisting to decide (whitelisting retains only the matching method, blacklist
omits any matching method). The default is blacklisting - pass `false` to change
the behavior.

### Zend\\Hydrator\\Filter\\NumberOfParameterFilter

This filter is used in the `ClassMethods` hydrator to check the number of
parameters. By convention, the `get`, `has` and `is` methods do not get any
parameters - but it may happen. You can add your own number of required
parameters, simply add the number to the constructor. The default value is 0. If
the method has more or fewer parameters than what the filter accepts, it will be
omitted.

## Remove filters

If you want to tell e.g. the `ClassMethods` hydrator, to not extract methods that start with `is`,
remove the related filter:

```php
$hydrator = new ClassMethods(false);
$hydrator->removeFilter('is');
```

After performing the above, the key/value pairs for `is` methods will not end up
in your extracted array anymore. The filters can be used in any hydrator, but
the `ClassMethods` hydrator is the only one, that has pre-registered filters:

```php
$this->filterComposite->addFilter('is', new IsFilter());
$this->filterComposite->addFilter('has', new HasFilter());
$this->filterComposite->addFilter('get', new GetFilter());
$this->filterComposite->addFilter(
    'parameter',
    new NumberOfParameterFilter(),
    FilterComposite::CONDITION_AND
);
```

If these are not appropriate for your object, you can unregister them as shown
in the previous example.

## Add filters

You can add filters to any hydrator that extends the `AbstractHydrator`. Filters
can either implement `FilterInterface`, or simply be PHP callables:

```php
$hydrator->addFilter('len', function($property) {
    if (strlen($property) !== 3) {
        return false;
    }
    return true;
});
```

By default, every filter you add will be added with a conditional `or`. If you
want to add it with `and` (as the `NumberOfParameterFilter` that is added to the
`ClassMethods` hydrator by default), provide the conditon as the third argument
to `addFilter`:

```php
$hydrator->addFilter('len', function($property) {
    if (strlen($property) !== 3) {
        return false;
    }
    return true;
}, FilterComposite::CONDITION_AND);
```

One common use case for filters is to omit getters for values that you do not
want to represent, such as a service manager instance:

```php
$hydrator->addFilter(
  'servicemanager',
  new MethodMatchFilter('getServiceManager'),
  FilterComposite::CONDITION_AND
);
```

The example above will exclude the `getServiceManager()` method and the
`servicemanager` key from extraction, even if the `get` filter wants to add it.

## Use FilterComposite for complex filters

`FilterComposite` implements `FilterInterface` as well, so you can add it as
a regular filter to the hydrator. One benefit of this implementation is that you
can add the filters with a condition and accomplish complex requirements using
different composites with different conditions. You can pass the following
conditions to the 3rd parameter, when you add a filter:

### Zend\\Hydrator\\Filter\\FilterComposite::CONDITION\_OR

At the given level of the composite, at least one filter in that condition block
has to return true to extract the value.

### Zend\\Hydrator\\Filter\\FilterComposite::CONDITION\_AND

At the given level of the composite, all filters in that condition block must
return true to extract the value.

### FilterComposite Examples

This composition will have a similar logic as the if below:

```php
$composite = new FilterComposite();

$composite->addFilter('one', $condition1);
$composite->addFilter('two', $condition2);
$composite->addFilter('three', $condition3);
$composite->addFilter('four', $condition4, FilterComposite::CONDITION_AND);
$composite->addFilter('five', $condition5, FilterComposite::CONDITION_AND);

// This is what's happening internally
if (
     ($condition1
        || $condition2
        || $condition3
     ) && ($condition4
        && $condition5
     )
) {
    //do extraction
}
```

If you only have one condition (e.g., only an `and` or `or`) block, the other
one will be completely ignored.

A bit more complex filter can look like this:

```php
$composite = new FilterComposite();
$composite->addFilter(
    'servicemanager',
    new MethodMatchFilter('getServiceManager'),
    FilterComposite::CONDITION_AND
);
$composite->addFilter(
    'eventmanager',
    new MethodMatchFilter('getEventManager'),
    FilterComposite::CONDITION_AND
);

$hydrator->addFilter('excludes', $composite, FilterComposite::CONDITION_AND);

// Internal
if (( // default composite inside the hydrator
        ($getFilter
            || $hasFilter
            || $isFilter
        ) && (
            $numberOfParameterFilter
        )
   ) && ( // new composite, added to the one above
        $serviceManagerFilter
        && $eventManagerFilter
   )
) {
    // do extraction
}
```

If you perform this on the `ClassMethods` hydrator, all getters will get
extracted, except for `getServiceManager()` and `getEventManager()`.

## Using the provider interface

`FilterProviderInterface` allows you to configure the behavior of the hydrator
inside your objects.

```php
namespace Zend\Hydrator\Filter;

interface FilterProviderInterface
{
    /**
     * Provides a filter for hydration
     *
     * @return FilterInterface
     */
    public function getFilter();
}
```

(The `getFilter()` method is automatically excluded from `extract()`.) If the
extracted object implements the `Zend\Hydrator\Filter\FilterProviderInterface`,
the returned `FilterInterface` instance can also be a `FilterComposite`.

For example:

```php
Class Foo implements FilterProviderInterface
{
     public function getFoo()
     {
         return 'foo';
     }

     public function hasFoo()
     {
         return true;
     }

     public function getServiceManager()
     {
         return 'servicemanager';
     }

     public function getEventManager()
     {
         return 'eventmanager';
     }

     public function getFilter()
     {
         $composite = new FilterComposite();
         $composite->addFilter('get', new GetFilter());

         $exclusionComposite = new FilterComposite();
         $exclusionComposite->addFilter(
             'servicemanager',
             new MethodMatchFilter('getServiceManager'),
             FilterComposite::CONDITION_AND
             );
         $exclusionComposite->addFilter(
             'eventmanager',
             new MethodMatchFilter('getEventManager'),
             FilterComposite::CONDITION_AND
         );

         $composite->addFilter('excludes', $exclusionComposite, FilterComposite::CONDITION_AND);

         return $composite;
     }
}

$hydrator = new ClassMethods(false);
$extractedArray = $hydrator->extract(new Foo());
```

`$extractedArray` will only have 'foo' =&gt; 'foo'; all other values are
excluded from extraction.

> ### Note
> 
> All pre-registered filters from the `ClassMethods` hydrator are ignored when
> this interface is used.
