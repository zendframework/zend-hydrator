<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace ZendTest\Hydrator;

use PHPUnit\Framework\TestCase;
use Zend\Hydrator\HydratorInterface;
use Zend\Hydrator\ObjectPropertyHydrator;
use Zend\Hydrator\Strategy\ClosureStrategy;

use function sprintf;

class HydratorClosureStrategyTest extends TestCase
{
    /**
     * The hydrator that is used during testing.
     *
     * @var HydratorInterface
     */
    private $hydrator;

    protected function setUp()
    {
        $this->hydrator = new ObjectPropertyHydrator();
    }

    public function testAddingStrategy()
    {
        $this->assertAttributeCount(0, 'strategies', $this->hydrator);

        $this->hydrator->addStrategy('myStrategy', new ClosureStrategy());

        $this->assertAttributeCount(1, 'strategies', $this->hydrator);
    }

    public function testCheckStrategyEmpty()
    {
        $this->assertFalse($this->hydrator->hasStrategy('myStrategy'));
    }

    public function testCheckStrategyNotEmpty()
    {
        $this->hydrator->addStrategy('myStrategy', new ClosureStrategy());

        $this->assertTrue($this->hydrator->hasStrategy('myStrategy'));
    }

    public function testRemovingStrategy()
    {
        $this->assertAttributeCount(0, 'strategies', $this->hydrator);

        $this->hydrator->addStrategy('myStrategy', new ClosureStrategy());
        $this->assertAttributeCount(1, 'strategies', $this->hydrator);

        $this->hydrator->removeStrategy('myStrategy');
        $this->assertAttributeCount(0, 'strategies', $this->hydrator);
    }

    public function testRetrieveStrategy()
    {
        $strategy = new ClosureStrategy();
        $this->hydrator->addStrategy('myStrategy', $strategy);

        $this->assertEquals($strategy, $this->hydrator->getStrategy('myStrategy'));
    }

    public function testExtractingObjects()
    {
        $this->hydrator->addStrategy('field1', new ClosureStrategy(
            function ($value) {
                return sprintf('%s', $value);
            },
            null
        ));
        $this->hydrator->addStrategy('field2', new ClosureStrategy(
            function ($value) {
                return sprintf('hello, %s!', $value);
            },
            null
        ));

        $entity = new TestAsset\HydratorClosureStrategyEntity(111, 'world');
        $values = $this->hydrator->extract($entity);

        $this->assertEquals(111, $values['field1']);
        $this->assertEquals('hello, world!', $values['field2']);
    }

    public function testHydratingObjects()
    {
        $this->hydrator->addStrategy('field2', new ClosureStrategy(
            null,
            function ($value) {
                return sprintf('hello, %s!', $value);
            }
        ));
        $this->hydrator->addStrategy('field3', new ClosureStrategy(
            null,
            function ($value) {
                return new TestAsset\HydratorClosureStrategyEntity($value, sprintf('111%s', $value));
            }
        ));

        $entity = new TestAsset\HydratorClosureStrategyEntity(111, 'world');

        $values = $this->hydrator->extract($entity);
        $values['field3'] = 333;

        $this->assertCount(2, (array) $entity);
        $this->hydrator->hydrate($values, $entity);
        $this->assertCount(3, (array) $entity);

        $this->assertInstanceOf(TestAsset\HydratorClosureStrategyEntity::class, $entity->field3);
    }
}
