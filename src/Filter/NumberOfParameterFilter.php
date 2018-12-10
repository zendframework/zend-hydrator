<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Zend\Hydrator\Filter;

use ReflectionException;
use ReflectionMethod;
use Zend\Hydrator\Exception\InvalidArgumentException;

use function sprintf;

class NumberOfParameterFilter implements FilterInterface
{
    /**
     * The number of parameters being accepted
     *
     * @var int
     */
    protected $numberOfParameters;

    /**
     * @param int $numberOfParameters Number of accepted parameters
     */
    public function __construct(int $numberOfParameters = 0)
    {
        $this->numberOfParameters = $numberOfParameters;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function filter(string $property) : bool
    {
        try {
            $reflectionMethod = new ReflectionMethod($property);
        } catch (ReflectionException $exception) {
            throw new InvalidArgumentException(sprintf(
                'Method %s does not exist',
                $property
            ));
        }

        return $reflectionMethod->getNumberOfParameters() === $this->numberOfParameters;
    }
}
