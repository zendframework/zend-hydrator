<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Zend\Hydrator;

trait HydratorAwareTrait
{
    /**
     * Hydrator instance
     *
     * @var null|HydratorInterface
     */
    protected $hydrator = null;

    /**
     * Set hydrator
     */
    public function setHydrator(HydratorInterface $hydrator) : void
    {
        $this->hydrator = $hydrator;
    }

    /**
     * Retrieve hydrator
     */
    public function getHydrator() : ?HydratorInterface
    {
        return $this->hydrator;
    }
}
