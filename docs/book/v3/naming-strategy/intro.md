# Naming Strategies

Sometimes, the representation of a property should not share the same name as
the property itself. As an example, when serializing an object for a JSON
payload, you may want to convert camelCase properties to underscore_separated
properties, and vice versa when deserializing JSON to an object.

To make that possible, zend-hydrator provides _naming strategies_. These are
similar to [strategies](../strategies.md), but instead of operating on the
_value_, they operate on the _name_.

## NamingStrategyInterface

Naming strategies implement `Zend\Hydrator\NamingStrategy\NamingStrategyInterface`:

```php
namespace Zend\Hydrator\NamingStrategy;

/**
 * Allow property extraction / hydration for hydrator
 */
interface NamingStrategyInterface
{
    /**
     * Converts the given name so that it can be extracted by the hydrator.
     *
     * @param null|mixed[] $data The original data for context.
     */
    public function hydrate(string $name, ?array $data = null) : string;

    /**
     * Converts the given name so that it can be hydrated by the hydrator.
     *
     * @param null|object $object The original object for context.
     */
    public function extract(string $name, ?object $object = null) : string;
}
```

## Providing naming strategies

Hydrators can indicate they will consume naming strategies, as well as allow
registration of them, by implementing `Zend\Hydrator\NamingStrategy\NamingStrategyEnabledInterface`:

```php
namespace Zend\Hydrator\NamingStrategy;

interface NamingStrategyEnabledInterface
{
    /**
     * Sets the naming strategy.
     */
    public function setNamingStrategy(NamingStrategyInterface $strategy) : void;

    /**
     * Gets the naming strategy.
     */
    public function getNamingStrategy() : NamingStrategyInterface;

    /**
     * Checks if a naming strategy exists.
     */
    public function hasNamingStrategy() : bool;

    /**
     * Removes the naming strategy.
     */
    public function removeNamingStrategy() : void;
}
```

We provide a default implementation of this interface within the
`Zend\Hydrator\AbstractHydrator` definition. Its `getNamingStrategy()` will
lazy-load an `IdentityNamingStrategy` if none has been previously registered.
Since all shipped hydrators extend `AbstractHydrator`, they can consume naming
strategies.

## Shipped naming strategies

We provide the following naming strategies:

- [CompositeNamingStrategy](composite-naming-strategy.md)
- [IdentityNamingStrategy](identity-naming-strategy.md)
- [MapNamingStrategy](map-naming-strategy.md)
- [UnderscoreNamingStrategy](underscore-naming-strategy.md)
