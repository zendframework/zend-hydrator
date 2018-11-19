<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace ZendTest\Hydrator\TestAsset;

class HydratorStrategyEntityA
{
    public $entities; // public to make testing easier!

    public function __construct()
    {
        $this->entities = [];
    }

    public function addEntity(HydratorStrategyEntityB $entity)
    {
        $this->entities[] = $entity;
    }

    public function getEntities()
    {
        return $this->entities;
    }

    public function setEntities($entities)
    {
        $this->entities = $entities;
    }

    // Add the getArrayCopy method so we can test the ArraySerializable hydrator:
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    // Add the populate method so we can test the ArraySerializable hydrator:
    public function populate($data)
    {
        foreach ($data as $name => $value) {
            $this->$name = $value;
        }
    }
}
