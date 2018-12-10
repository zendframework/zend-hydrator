<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace ZendTest\Hydrator\TestAsset;

class HydratorClosureStrategyEntity
{
    public $field1;
    public $field2;

    public function __construct($field1 = null, $field2 = null)
    {
        $this->field1 = $field1;
        $this->field2 = $field2;
    }
}
