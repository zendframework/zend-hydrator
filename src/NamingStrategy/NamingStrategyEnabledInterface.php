<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Zend\Hydrator\NamingStrategy;

interface NamingStrategyEnabledInterface
{
    /**
     * Adds the given naming strategy
     */
    public function setNamingStrategy(NamingStrategyInterface $strategy) : void;

    /**
     * Gets the naming strategy.
     */
    public function getNamingStrategy() : NamingStrategyInterface;

    /**
     * Checks if a naming strategy exists.
     */
    public function hasNamingStrategy() : bool;

    /**
     * Removes the naming with the given name.
     */
    public function removeNamingStrategy() : void;
}
