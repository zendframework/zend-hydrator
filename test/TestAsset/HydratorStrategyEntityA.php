<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

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
