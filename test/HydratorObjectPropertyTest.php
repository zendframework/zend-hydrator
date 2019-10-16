<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace ZendTest\Hydrator;

use PHPUnit\Framework\TestCase;
use Zend\Hydrator\ObjectPropertyHydrator;

class HydratorObjectPropertyTest extends TestCase
{
    /**
     * @var ObjectPropertyHydrator
     */
    private $hydrator;

    protected function setUp() : void
    {
        $this->hydrator = new ObjectPropertyHydrator();
    }

    public function testMultipleInvocationsWithDifferentFiltersFindsAllProperties()
    {
        $instance = (object) [];

        $instance->id         = 4;
        $instance->array      = [4, 3, 5, 6];
        $instance->object     = (object) [];
        $instance->object->id = 4;

        $this->hydrator->addFilter('values', function ($property) {
            return true;
        });
        $result = $this->hydrator->extract($instance);
        $this->assertArrayHasKey('id', $result);
        $this->assertEquals($instance->id, $result['id']);
        $this->assertArrayHasKey('array', $result);
        $this->assertEquals($instance->array, $result['array']);
        $this->assertArrayHasKey('object', $result);
        $this->assertSame($instance->object, $result['object']);

        $this->hydrator->removeFilter('values');
        $this->hydrator->addFilter('complex', function ($property) {
            switch ($property) {
                case 'array':
                case 'object':
                    return false;
                default:
                    return true;
            }
        });
        $result = $this->hydrator->extract($instance);
        $this->assertArrayHasKey('id', $result);
        $this->assertEquals($instance->id, $result['id']);
        $this->assertArrayNotHasKey('array', $result);
        $this->assertArrayNotHasKey('object', $result);
    }
}
