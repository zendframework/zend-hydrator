<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendTest\Hydrator\TestAsset;

/**
 * Test asset to set options from a Traversable object
 */
class ClassMethodsTraversableOptions implements \Iterator
{
    protected $options = [
        'underscoreSeparatedKeys' => false,
    ];

    public function current()
    {
        return current($this->options);
    }

    public function next()
    {
        next($this->options);
    }

    public function key()
    {
        return key($this->options);
    }

    public function valid()
    {
        return isset($this->options[$this->key()]);
    }

    public function rewind()
    {
        reset($this->options);
    }
}
