<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Zend\Hydrator\NamingStrategy;

use Zend\Hydrator\Exception;

use function array_flip;
use function array_key_exists;
use function array_walk;
use function is_int;
use function is_string;
use function sprintf;

final class MapNamingStrategy implements NamingStrategyInterface
{
    /**
     * @var string[]
     */
    private $extractionMap = [];

    /**
     * @var string[]
     */
    private $hydrationMap = [];

    /**
     * @param null|string[] $hydrationMap A map of string keys and values for
     *     translation of hydrated field names. If not provided, the result of
     *     an array_flip($extractionMap) will be used.
     * @param null|string[] $extractionMap A map of string keys and values for
     *     translation of extracted field names. If not provided, the result of
     *     an array_flip($hydrationMap) will be used.
     * @throws Exception\InvalidArgumentException if neither $hydrationMap nor
     *     $extractionMap are provided.
     * @throws Exception\InvalidArgumentException when flipping either the
     *     $hydrationMap or $extractionMap, and any value is a non-string,
     *     non-int value.
     */
    public function __construct(?array $hydrationMap = null, ?array $extractionMap = null)
    {
        if (null === $hydrationMap && null === $extractionMap) {
            throw new Exception\InvalidArgumentException(sprintf(
                '%s requires one or both of an array $hydrationMap and array $extractionMap;'
                . ' neither provided',
                __CLASS__
            ));
        }

        if (null === $extractionMap) {
            $extractionMap = $this->flipMapping($hydrationMap);
        }

        if (null === $hydrationMap) {
            $hydrationMap = $this->flipMapping($extractionMap);
        }

        $this->extractionMap = $extractionMap;
        $this->hydrationMap  = $hydrationMap;
    }

    /**
     * {@inheritDoc}
     */
    public function extract(string $name, ?object $object = null) : string
    {
        return array_key_exists($name, $this->extractionMap)
            ? $this->extractionMap[$name]
            : $name;
    }

    /**
     * {@inheritDoc}
     */
    public function hydrate(string $name, ?array $data = null) : string
    {
        return array_key_exists($name, $this->hydrationMap)
            ? $this->hydrationMap[$name]
            : $name;
    }

    /**
     * Safely flip mapping array.
     *
     * @param  string[] $array Array to flip
     * @return string[] Flipped array
     * @throws Exception\InvalidArgumentException if any value of the $array is
     *     a non-string, non-int value.
     */
    private function flipMapping(array $array) : array
    {
        array_walk($array, function ($value) {
            if (! is_string($value) && ! is_int($value)) {
                throw new Exception\InvalidArgumentException(
                    'Mapping array can not be flipped because of invalid value'
                );
            }
        });

        return array_flip($array);
    }
}
