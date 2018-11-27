<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Zend\Hydrator\Iterator;

use Iterator;
use IteratorIterator;
use Zend\Hydrator\Exception\InvalidArgumentException;
use Zend\Hydrator\HydratorInterface;

use function class_exists;
use function is_object;
use function sprintf;

class HydratingIteratorIterator extends IteratorIterator implements HydratingIteratorInterface
{
    /**
     * @var HydratorInterface
     */
    protected $hydrator;

    /**
     * @var object
     */
    protected $prototype;

    /**
     * @param HydratorInterface $hydrator
     * @param Iterator $data
     * @param string|object $prototype Object or class name to use for prototype.
     */
    public function __construct(HydratorInterface $hydrator, Iterator $data, $prototype)
    {
        $this->setHydrator($hydrator);
        $this->setPrototype($prototype);
        parent::__construct($data);
    }

    /**
     * @inheritdoc
     */
    public function setPrototype($prototype) : void
    {
        if (is_object($prototype)) {
            $this->prototype = $prototype;
            return;
        }

        if (! class_exists($prototype)) {
            throw new InvalidArgumentException(
                sprintf('Method %s was passed an invalid class name: %s', __METHOD__, $prototype)
            );
        }

        $this->prototype = new $prototype;
    }

    /**
     * @inheritdoc
     */
    public function setHydrator(HydratorInterface $hydrator) : void
    {
        $this->hydrator = $hydrator;
    }

    /**
     * @return object Returns hydrated clone of $prototype
     */
    public function current()
    {
        $currentValue = parent::current();
        $object       = clone $this->prototype;
        $this->hydrator->hydrate($currentValue, $object);
        return $object;
    }
}
