<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace ZendTest\Hydrator\TestAsset;

class ObjectProperty
{
    public $foo = null;
    public $bar = null;
    public $blubb = null;
    public $quo = null;
    protected $quin = null;

    public function __construct()
    {
        $this->foo = "bar";
        $this->bar = "foo";
        $this->blubb = "baz";
        $this->quo = "blubb";
        $this->quin = 'five';
    }

    public function get(string $name)
    {
        return $this->$name;
    }
}
