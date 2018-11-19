<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Zend\Hydrator\Strategy;

use Zend\Hydrator\Exception\InvalidArgumentException;
use Zend\Serializer\Adapter\AdapterInterface as SerializerAdapter;
use Zend\Serializer\Serializer as SerializerFactory;

class SerializableStrategy implements StrategyInterface
{
    /**
     * @var string|SerializerAdapter
     */
    protected $serializer;

    /**
     * @var array
     */
    protected $serializerOptions = [];

    /**
     *
     * @param mixed $serializer string or SerializerAdapter
     * @param mixed $serializerOptions
     */
    public function __construct($serializer, ?iterable $serializerOptions = null)
    {
        $this->setSerializer($serializer);
        if ($serializerOptions) {
            $this->setSerializerOptions($serializerOptions);
        }
    }

    /**
     * Serialize the given value so that it can be extracted by the hydrator.
     *
     * @param mixed $value The original value.
     * @return mixed Returns the value that should be extracted.
     */
    public function extract($value, ?object $object = null)
    {
        $serializer = $this->getSerializer();
        return $serializer->serialize($value);
    }

    /**
     * Unserialize the given value so that it can be hydrated by the hydrator.
     *
     * @param mixed $value The original value.
     * @return mixed Returns the value that should be hydrated.
     */
    public function hydrate($value, ?array $data = null)
    {
        $serializer = $this->getSerializer();
        return $serializer->unserialize($value);
    }

    /**
     * Set serializer
     *
     * @param  string|SerializerAdapter $serializer
     * @throws InvalidArgumentException for invalid $serializer values
     */
    public function setSerializer($serializer) : void
    {
        if (! is_string($serializer) && ! $serializer instanceof SerializerAdapter) {
            throw new InvalidArgumentException(sprintf(
                '%s expects either a string serializer name or Zend\Serializer\Adapter\AdapterInterface instance; '
                . 'received "%s"',
                __METHOD__,
                (is_object($serializer) ? get_class($serializer) : gettype($serializer))
            ));
        }
        $this->serializer = $serializer;
    }

    /**
     * Get serializer
     */
    public function getSerializer() : SerializerAdapter
    {
        if (is_string($this->serializer)) {
            $options = $this->getSerializerOptions();
            $this->setSerializer(SerializerFactory::factory($this->serializer, $options));
        } elseif (null === $this->serializer) {
            $this->setSerializer(SerializerFactory::getDefaultAdapter());
        }

        return $this->serializer;
    }

    /**
     * Set configuration options for instantiating a serializer adapter
     */
    public function setSerializerOptions(iterable $serializerOptions) : void
    {
        $this->serializerOptions = is_array($serializerOptions)
            ? $serializerOptions
            : iterator_to_array($serializerOptions);
    }

    /**
     * Get configuration options for instantiating a serializer adapter
     */
    public function getSerializerOptions() : array
    {
        return $this->serializerOptions;
    }
}
