<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace ZendTest\Hydrator\TestAsset;

use function strlen;
use function strtolower;
use function substr;

class ClassMethodsMagicMethodSetter
{
    protected $foo;

    public function __call($method, $args)
    {
        if (strlen($method) > 3 && strtolower(substr($method, 3)) == 'foo') {
            $this->foo = $args[0];
        }
    }

    public function getFoo()
    {
        return $this->foo;
    }
}
