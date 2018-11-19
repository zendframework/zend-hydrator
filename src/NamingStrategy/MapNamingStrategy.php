<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Zend\Hydrator\NamingStrategy;

use Zend\Hydrator\Exception\InvalidArgumentException;

class MapNamingStrategy implements NamingStrategyInterface
{
    /**
     * Map for hydrate name conversion.
     *
     * @var array
     */
    protected $mapping = [];

    /**
     * Reversed map for extract name conversion.
     *
     * @var array
     */
    protected $reverse = [];

    /**
     * @param array $mapping Map for name conversion on hydration
     * @param array $reverse Reverse map for name conversion on extraction
     */
    public function __construct(array $mapping, ?array $reverse = null)
    {
        $this->mapping = $mapping;
        $this->reverse = $reverse ?: $this->flipMapping($mapping);
    }

    /**
     * Safely flip mapping array.
     *
     * @param  array $array Array to flip
     * @return array Flipped array
     * @throws InvalidArgumentException
     */
    protected function flipMapping(array $array) : array
    {
        array_walk($array, function ($value) {
            if (! is_string($value) && ! is_int($value)) {
                throw new InvalidArgumentException('Mapping array can\'t be flipped because of invalid value');
            }
        });

        return array_flip($array);
    }

    /**
     * Converts the given name so that it can be extracted by the hydrator.
     */
    public function hydrate(string $name, ?array $data = null) : string
    {
        if (array_key_exists($name, $this->mapping)) {
            return $this->mapping[$name];
        }

        return $name;
    }

    /**
     * Converts the given name so that it can be hydrated by the hydrator.
     */
    public function extract(string $name, ?object $object = null) : string
    {
        if (array_key_exists($name, $this->reverse)) {
            return $this->reverse[$name];
        }

        return $name;
    }
}
