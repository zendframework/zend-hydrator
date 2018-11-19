<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Zend\Hydrator;

class ArraySerializable extends AbstractHydrator
{
    /**
     * Extract values from the provided object
     *
     * Extracts values via the object's getArrayCopy() method.
     *
     * @throws Exception\BadMethodCallException for an $object not implementing getArrayCopy()
     */
    public function extract(object $object) : array
    {
        if (! is_callable([$object, 'getArrayCopy'])) {
            throw new Exception\BadMethodCallException(
                sprintf('%s expects the provided object to implement getArrayCopy()', __METHOD__)
            );
        }

        $data   = $object->getArrayCopy();
        $filter = $this->getFilter();

        foreach ($data as $name => $value) {
            if (! $filter->filter($name)) {
                unset($data[$name]);
                continue;
            }

            $extractedName = $this->extractName($name, $object);

            // replace the original key with extracted, if differ
            if ($extractedName !== $name) {
                unset($data[$name]);
                $name = $extractedName;
            }

            $data[$name] = $this->extractValue($name, $value, $object);
        }

        return $data;
    }

    /**
     * Hydrate an object
     *
     * Hydrates an object by passing $data to either its exchangeArray() or
     * populate() method.
     *
     * @param  object $object
     * @return object
     * @throws Exception\BadMethodCallException for an $object not implementing exchangeArray() or populate()
     */
    public function hydrate(array $data, object $object) : object
    {
        $replacement = [];
        foreach ($data as $key => $value) {
            $name = $this->hydrateName($key, $data);
            $replacement[$name] = $this->hydrateValue($name, $value, $data);
        }

        if (is_callable([$object, 'exchangeArray'])) {
            // Ensure any previously populated values not in the replacement
            // remain following population.
            if (is_callable([$object, 'getArrayCopy'])) {
                $original = $object->getArrayCopy($object);
                $replacement = array_merge($original, $replacement);
            }
            $object->exchangeArray($replacement);
            return $object;
        }

        if (is_callable([$object, 'populate'])) {
            $object->populate($replacement);
            return $object;
        }

        throw new Exception\BadMethodCallException(
            sprintf('%s expects the provided object to implement exchangeArray() or populate()', __METHOD__)
        );
    }
}
