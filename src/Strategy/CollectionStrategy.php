<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2017 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

namespace Zend\Hydrator\Strategy;

use ReflectionClass;
use Zend\Hydrator\HydratorInterface;
use Zend\Hydrator\Exception;

class CollectionStrategy implements StrategyInterface
{
    /**
     * @var HydratorInterface
     */
    private $objectHydrator;

    /**
     * @var string
     */
    private $objectClassName;

    /**
     * @param HydratorInterface $objectHydrator
     * @param string $objectClassName
     *
     * @throws Exception\InvalidArgumentException
     */
    public function __construct(HydratorInterface $objectHydrator, $objectClassName)
    {
        if (! is_string($objectClassName) || ! class_exists($objectClassName)) {
            throw new Exception\InvalidArgumentException(sprintf(
                'Object class name needs to the name of an existing class, got "%s" instead.',
                is_object($objectClassName) ? get_class($objectClassName) : gettype($objectClassName)
            ));
        }

        $this->objectHydrator = $objectHydrator;
        $this->objectClassName = $objectClassName;
    }

    /**
     * Converts the given value so that it can be extracted by the hydrator.
     *
     * @param array $value The original value.
     * @throws Exception\InvalidArgumentException
     * @return mixed Returns the value that should be extracted.
     */
    public function extract($value)
    {
        if (! is_array($value)) {
            throw new Exception\InvalidArgumentException(sprintf(
                'Value needs to be an array, got "%s" instead.',
                is_object($value) ? get_class($value) : gettype($value)
            ));
        }

        return array_map(function ($object) {
            if (! $object instanceof $this->objectClassName) {
                throw new Exception\InvalidArgumentException(sprintf(
                    'Value needs to be an instance of "%s", got "%s" instead.',
                    $this->objectClassName,
                    is_object($object) ? get_class($object) : gettype($object)
                ));
            }

            return $this->objectHydrator->extract($object);
        }, $value);
    }

    /**
     * Converts the given value so that it can be hydrated by the hydrator.
     *
     * @param array $value The original value.
     * @throws Exception\InvalidArgumentException
     * @return mixed Returns the value that should be hydrated.
     */
    public function hydrate($value)
    {
        if (! is_array($value)) {
            throw new Exception\InvalidArgumentException(sprintf(
                'Value needs to be an array, got "%s" instead.',
                is_object($value) ? get_class($value) : gettype($value)
            ));
        }

        $reflection = new ReflectionClass($this->objectClassName);

        return array_map(function ($data) use ($reflection) {
            return $this->objectHydrator->hydrate(
                $data,
                $reflection->newInstanceWithoutConstructor()
            );
        }, $value);
    }
}
