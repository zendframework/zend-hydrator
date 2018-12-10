<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Zend\Hydrator\Strategy;

interface StrategyEnabledInterface
{
    /**
     * Adds the given strategy under the given name.
     */
    public function addStrategy(string $name, StrategyInterface $strategy) : void;

    /**
     * Gets the strategy with the given name.
     */
    public function getStrategy(string $name) : StrategyInterface;

    /**
     * Checks if the strategy with the given name exists.
     */
    public function hasStrategy(string $name) : bool;

    /**
     * Removes the strategy with the given name.
     */
    public function removeStrategy(string $name) : void;
}
