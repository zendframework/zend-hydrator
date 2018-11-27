<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Zend\Hydrator\Filter;

use ArrayObject;
use Zend\Hydrator\Exception\InvalidArgumentException;

class FilterComposite implements FilterInterface
{
    /**
     * Constant to add with "or" condition
     */
    public const CONDITION_OR = 1;

    /**
     * Constant to add with "and" condition
     */
    public const CONDITION_AND = 2;

    /**
     * @var ArrayObject
     */
    protected $andFilter;

    /**
     * @var ArrayObject
     */
    protected $orFilter;

    /**
     * We can pass a list of OR/AND filters through construct
     *
     * @param callable[]|FilterInterface[] $orFilters
     * @param callable[]|FilterInterface[] $andFilters
     * @throws InvalidArgumentException
     */
    public function __construct(array $orFilters = [], array $andFilters = [])
    {
        array_walk($orFilters, [$this, 'validateFilter']);
        array_walk($andFilters, [$this, 'validateFilter']);

        $this->orFilter = new ArrayObject($orFilters);
        $this->andFilter = new ArrayObject($andFilters);
    }

    /**
     * Add a filter to the composite. Has to be indexed with $name in
     * order to identify a specific filter.
     *
     * This example will exclude all methods from the hydration, that starts with 'getService'
     * <code>
     * $composite->addFilter('exclude',
     *     function ($method) {
     *         if (preg_match('/^getService/', $method) {
     *             return false;
     *         }
     *         return true;
     *     }, FilterComposite::CONDITION_AND
     * );
     * </code>
     *
     * @param  callable|FilterInterface $filter
     * @param  int                      $condition Can be either
     *     FilterComposite::CONDITION_OR or FilterComposite::CONDITION_AND
     * @throws InvalidArgumentException
     */
    public function addFilter(string $name, $filter, int $condition = self::CONDITION_OR) : void
    {
        $this->validateFilter($filter, $name);

        if ($condition === self::CONDITION_OR) {
            $this->orFilter[$name] = $filter;
            return;
        }

        if ($condition === self::CONDITION_AND) {
            $this->andFilter[$name] = $filter;
            return;
        }
    }

    /**
     * Check if $name has a filter registered
     */
    public function hasFilter(string $name) : bool
    {
        return isset($this->orFilter[$name]) || isset($this->andFilter[$name]);
    }

    /**
     * Remove a filter from the composition
     */
    public function removeFilter(string $name) : void
    {
        if (isset($this->orFilter[$name])) {
            unset($this->orFilter[$name]);
        }

        if (isset($this->andFilter[$name])) {
            unset($this->andFilter[$name]);
        }
    }

    /**
     * Filter the composite based on the AND and OR condition
     *
     * Will return true if one from the "or conditions" and all from
     * the "and condition" returns true. Otherwise false
     *
     * @param string $property Parameter will be e.g. Parent\Namespace\Class::method
     */
    public function filter(string $property) : bool
    {
        $andCount = count($this->andFilter);
        $orCount = count($this->orFilter);
        // return true if no filters are registered
        if ($orCount === 0 && $andCount === 0) {
            return true;
        }

        $returnValue = $orCount === 0 && $andCount !== 0;

        // Check if 1 from the or filters return true
        foreach ($this->orFilter as $filter) {
            if (is_callable($filter)) {
                if ($filter($property) === true) {
                    $returnValue = true;
                    break;
                }
                continue;
            }

            if ($filter->filter($property) === true) {
                $returnValue = true;
                break;
            }
        }

        // Check if all of the and condition return true
        foreach ($this->andFilter as $filter) {
            if (is_callable($filter)) {
                if ($filter($property) === false) {
                    return false;
                }
                continue;
            }

            if ($filter->filter($property) === false) {
                return false;
            }
        }

        return $returnValue;
    }

    /**
     * @param mixed $filter Filters should be callable or
     *     FilterInterface instances.
     * @throws InvalidArgumentException if $filter is neither a
     *     callable nor FilterInterface
     */
    private function validateFilter($filter, string $name) : void
    {
        if (! is_callable($filter) && ! $filter instanceof FilterInterface) {
            throw new InvalidArgumentException(sprintf(
                'The value of %s should be either a callable or an instance of %s',
                $name,
                FilterInterface::class
            ));
        }
    }
}
