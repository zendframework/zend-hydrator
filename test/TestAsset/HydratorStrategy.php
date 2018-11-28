<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace ZendTest\Hydrator\TestAsset;

use Zend\Hydrator\Strategy\DefaultStrategy;

class HydratorStrategy extends DefaultStrategy
{
    /**
     * A simulated storage device which is just an array with Car objects.
     *
     * @var array
     */
    private $simulatedStorageDevice;

    public function __construct()
    {
        $this->simulatedStorageDevice = [];
        $this->simulatedStorageDevice[] = new HydratorStrategyEntityB(111, 'AAA');
        $this->simulatedStorageDevice[] = new HydratorStrategyEntityB(222, 'BBB');
        $this->simulatedStorageDevice[] = new HydratorStrategyEntityB(333, 'CCC');
    }

    public function extract($value, ?object $object = null)
    {
        $result = [];
        foreach ($value as $instance) {
            $result[] = $instance->getField1();
        }
        return $result;
    }

    public function hydrate($value, ?array $data = null)
    {
        $result = $value;
        if (is_array($value)) {
            $result = [];
            foreach ($value as $field1) {
                $result[] = $this->findEntity($field1);
            }
        }
        return $result;
    }

    private function findEntity($field1)
    {
        $result = null;
        foreach ($this->simulatedStorageDevice as $entity) {
            if ($entity->getField1() == $field1) {
                $result = $entity;
                break;
            }
        }
        return $result;
    }
}
