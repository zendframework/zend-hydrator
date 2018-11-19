<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Zend\Hydrator\NamingStrategy;

final class IdentityNamingStrategy implements NamingStrategyInterface
{
    /**
     * {@inheritDoc}
     */
    public function hydrate(string $name, ?array $data = null) : string
    {
        return $name;
    }

    /**
     * {@inheritDoc}
     */
    public function extract(string $name, ?object $object = null) : string
    {
        return $name;
    }
}
