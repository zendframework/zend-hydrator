<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace ZendTest\Hydrator\TestAsset;

/**
 * Test asset to verify that a composition of a class-methods and an array-serializable
 * hydrator produces the expected output
 */
class AggregateObject
{
    /**
     * @var array
     */
    public $arrayData  = ['president' => 'Zaphod'];

    /**
     * @var string
     */
    public $maintainer = 'Marvin';

    /**
     * @return string
     */
    public function getMaintainer()
    {
        return $this->maintainer;
    }

    /**
     * @param string $maintainer
     */
    public function setMaintainer($maintainer)
    {
        $this->maintainer = $maintainer;
    }

    /**
     * @return array
     */
    public function getArrayCopy()
    {
        return $this->arrayData;
    }

    /**
     * @param array $data
     */
    public function exchangeArray(array $data)
    {
        $this->arrayData = $data;
    }
}
