<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace ZendTest\Hydrator\TestAsset;

class ClassWithPublicStaticProperties
{
    /**
     * @var string
     */
    public static $foo = 'foo';

    /**
     * @var string
     */
    public static $bar = 'bar';

    /**
     * @var string
     */
    public static $baz = 'baz';
}
