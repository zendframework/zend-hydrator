<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

declare(strict_types=1);

namespace ZendTest\Hydrator\TestAsset;

use Zend\Stdlib\ArraySerializableInterface;

class ArraySerializable implements ArraySerializableInterface
{
    protected $data = [];

    public function __construct()
    {
        $this->data = [
            "foo" => "bar",
            "bar" => "foo",
            "blubb" => "baz",
            "quo" => "blubb"
        ];
    }

    /**
     * Exchange internal values from provided array
     *
     * @param  array $array
     * @return void
     */
    public function exchangeArray(array $array)
    {
        $this->data = $array;
    }

    /**
     * Return an array representation of the object
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return $this->data;
    }
}
