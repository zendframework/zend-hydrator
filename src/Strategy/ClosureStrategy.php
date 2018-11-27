<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Zend\Hydrator\Strategy;

class ClosureStrategy implements StrategyInterface
{
    /**
     * Function, used in extract method, default:
     *
     * <code>
     * function ($value) {
     *     return $value;
     * };
     * </code>
     *
     * @var null|callable
     */
    protected $extractFunc = null;

    /**
     * Function, used in hydrate method, default:
     *
     * <code>
     * function ($value) {
     *     return $value;
     * };
     * </code>
     *
     * @var null|callable
     */
    protected $hydrateFunc = null;

    /**
     * You can describe how your values will extract and hydrate, like this:
     *
     * <code>
     * $hydrator->addStrategy('category', new ClosureStrategy(
     *     function (Category $value) {
     *         return (int) $value->id;
     *     },
     *     function ($value) {
     *         return new Category((int) $value);
     *     }
     * ));
     * </code>
     *
     * @param null|callable $extractFunc function for extracting values from an object
     * @param null|callable $hydrateFunc function for hydrating values to an object
     */
    public function __construct(?callable $extractFunc = null, ?callable $hydrateFunc = null)
    {
        $this->extractFunc = $extractFunc;
        $this->hydrateFunc = $hydrateFunc;
    }

    /**
     * Converts the given value so that it can be extracted by the hydrator.
     *
     * {@inheritDoc}
     */
    public function extract($value, ?object $object = null)
    {
        $func = $this->extractFunc;
        return $func
            ? $func($value, $object)
            : $value;
    }

    /**
     * Converts the given value so that it can be hydrated by the hydrator.
     *
     * {@inheritDoc}
     */
    public function hydrate($value, ?array $data = null)
    {
        $func = $this->hydrateFunc;
        return $func
            ? $func($value, $data)
            : $value;
    }
}
