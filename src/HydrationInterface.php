<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Zend\Hydrator;

interface HydrationInterface
{
    /**
     * Hydrate $object with the provided $data.
     *
     * @param mixed[] $data
     * @return object The implementation should return an object of any type.
     *     By purposely omitting the return type from the signature,
     *     implementations may choose to specify a more specific type.
     */
    public function hydrate(array $data, object $object);
}
