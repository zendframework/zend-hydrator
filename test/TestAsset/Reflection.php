<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace ZendTest\Hydrator\TestAsset;

class Reflection
{
    public $foo = '1';

    protected $fooBar = '2';

    private $fooBarBaz = '3';

    public function getFooBar()
    {
        return $this->fooBar;
    }

    public function getFooBarBaz()
    {
        return $this->fooBarBaz;
    }
}
