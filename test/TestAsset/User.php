<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2017 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

namespace ZendTest\Hydrator\TestAsset;

final class User
{
    /**
     * @var string
     */
    private $name;

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }
}
