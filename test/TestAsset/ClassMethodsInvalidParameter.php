<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace ZendTest\Hydrator\TestAsset;

class ClassMethodsInvalidParameter
{
    public function hasAlias($alias)
    {
        return $alias;
    }

    public function getTest($foo)
    {
        return $foo;
    }

    public function isTest($bar)
    {
        return $bar;
    }

    public function hasBar()
    {
        return true;
    }

    public function getFoo()
    {
        return "Bar";
    }

    public function isBla()
    {
        return false;
    }
}
