<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Zend\Hydrator\Exception;

use Psr\Container\NotFoundExceptionInterface;

use function sprintf;

class MissingHydratorServiceException extends InvalidArgumentException implements NotFoundExceptionInterface
{
    public static function forService(string $serviceName) : self
    {
        return new self(sprintf(
            'Unable to resolve "%s" to a hydrator service.',
            $serviceName
        ));
    }
}
