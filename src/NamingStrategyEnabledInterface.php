<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Zend\Hydrator;

interface NamingStrategyEnabledInterface
{
    /**
     * Adds the given naming strategy
     *
     * @param NamingStrategy\NamingStrategyInterface $strategy The naming to register.
     */
    public function setNamingStrategy(NamingStrategy\NamingStrategyInterface $strategy) : void;

    /**
     * Gets the naming strategy.
     */
    public function getNamingStrategy() : NamingStrategy\NamingStrategyInterface;

    /**
     * Checks if a naming strategy exists.
     */
    public function hasNamingStrategy() : bool;

    /**
     * Removes the naming with the given name.
     */
    public function removeNamingStrategy() : void;
}
