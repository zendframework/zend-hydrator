<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace ZendTest\Hydrator\TestAsset;

/**
 * Test asset to check how optional parameters of are treated methods
 */
class ClassMethodsOptionalParameters
{
    /**
     * @var string
     */
    public $foo = 'bar';

    /**
     * @param mixed $optional
     *
     * @return string
     */
    public function getFoo($optional = null)
    {
        return $this->foo;
    }

    /**
     * @param string $foo
     * @param mixed  $optional
     */
    public function setFoo($foo, $optional = null)
    {
        $this->foo = (string) $foo;
    }
}
