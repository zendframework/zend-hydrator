<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Zend\Hydrator\Strategy;

final class ExplodeStrategy implements StrategyInterface
{
    /**
     * @var string
     */
    private $valueDelimiter;

    /**
     * @var int|null
     */
    private $explodeLimit;

    /**
     * Constructor
     *
     * @param string   $delimiter    String that the values will be split upon
     * @param int|null $explodeLimit Explode limit
     */
    public function __construct(string $delimiter = ',', ?int $explodeLimit = null)
    {
        $this->setValueDelimiter($delimiter);
        $this->explodeLimit = ($explodeLimit === null) ? null : $explodeLimit;
    }

    /**
     * Sets the delimiter string that the values will be split upon
     */
    private function setValueDelimiter(string $delimiter) : void
    {
        if (empty($delimiter)) {
            throw new Exception\InvalidArgumentException('Delimiter cannot be empty.');
        }

        $this->valueDelimiter = $delimiter;
    }

    /**
     * {@inheritDoc}
     *
     * Split a string by delimiter
     *
     * @param string|null $value
     * @return string[]
     * @throws Exception\InvalidArgumentException
     */
    public function hydrate($value, ?array $data = null)
    {
        if (null === $value) {
            return [];
        }

        if (! (is_string($value) || is_numeric($value))) {
            throw new Exception\InvalidArgumentException(sprintf(
                '%s expects argument 1 to be string, %s provided instead',
                __METHOD__,
                is_object($value) ? get_class($value) : gettype($value)
            ));
        }

        if ($this->explodeLimit !== null) {
            return explode($this->valueDelimiter, (string) $value, $this->explodeLimit);
        }

        return explode($this->valueDelimiter, (string) $value);
    }

    /**
     * {@inheritDoc}
     *
     * Join array elements with delimiter
     *
     * @param string[] $value The original value.
     * @return string|null
     */
    public function extract($value, ?object $object = null)
    {
        if (! is_array($value)) {
            throw new Exception\InvalidArgumentException(sprintf(
                '%s expects argument 1 to be array, %s provided instead',
                __METHOD__,
                is_object($value) ? get_class($value) : gettype($value)
            ));
        }

        return empty($value) ? null : implode($this->valueDelimiter, $value);
    }
}
