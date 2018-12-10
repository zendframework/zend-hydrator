<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Zend\Hydrator\Filter;

interface FilterInterface
{
    /**
     * Should return true, if the given filter does not match
     *
     * @param string $property The name of the property
     */
    public function filter(string $property) : bool;
}
