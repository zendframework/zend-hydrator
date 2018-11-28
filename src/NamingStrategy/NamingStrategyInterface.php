<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Zend\Hydrator\NamingStrategy;

/**
 * Allow property extraction / hydration for hydrator
 */
interface NamingStrategyInterface
{
    /**
     * Converts the given name so that it can be extracted by the hydrator.
     *
     * @param null|mixed[] $data The original data for context.
     */
    public function hydrate(string $name, ?array $data = null) : string;

    /**
     * Converts the given name so that it can be hydrated by the hydrator.
     *
     * @param object $object The original object for context.
     */
    public function extract(string $name, ?object $object = null) : string;
}
