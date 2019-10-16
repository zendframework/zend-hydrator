<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace ZendTest\Hydrator;

use PHPUnit\Framework\TestCase;
use Zend\Hydrator\ClassMethodsHydrator;
use Zend\Hydrator\HydratorInterface;
use Zend\Hydrator\Strategy\StrategyInterface;

class HydratorStrategyTest extends TestCase
{
    /**
     * The hydrator that is used during testing.
     *
     * @var HydratorInterface
     */
    private $hydrator;

    protected function setUp() : void
    {
        $this->hydrator = new ClassMethodsHydrator();
    }

    public function testAddingStrategy()
    {
        $this->assertFalse($this->hydrator->hasStrategy('myStrategy'));

        $this->hydrator->addStrategy('myStrategy', new TestAsset\HydratorStrategy());

        $this->assertTrue($this->hydrator->hasStrategy('myStrategy'));
    }

    public function testCheckStrategyEmpty()
    {
        $this->assertFalse($this->hydrator->hasStrategy('myStrategy'));
    }

    public function testCheckStrategyNotEmpty()
    {
        $this->hydrator->addStrategy('myStrategy', new TestAsset\HydratorStrategy());

        $this->assertTrue($this->hydrator->hasStrategy('myStrategy'));
    }

    public function testRemovingStrategy()
    {
        $this->assertFalse($this->hydrator->hasStrategy('myStrategy'));

        $this->hydrator->addStrategy('myStrategy', new TestAsset\HydratorStrategy());
        $this->assertTrue($this->hydrator->hasStrategy('myStrategy'));

        $this->hydrator->removeStrategy('myStrategy');
        $this->assertFalse($this->hydrator->hasStrategy('myStrategy'));
    }

    public function testRetrieveStrategy()
    {
        $strategy = new TestAsset\HydratorStrategy();
        $this->hydrator->addStrategy('myStrategy', $strategy);

        $this->assertEquals($strategy, $this->hydrator->getStrategy('myStrategy'));
    }

    public function testExtractingObjects()
    {
        $this->hydrator->addStrategy('entities', new TestAsset\HydratorStrategy());

        $entityA = new TestAsset\HydratorStrategyEntityA();
        $entityA->addEntity(new TestAsset\HydratorStrategyEntityB(111, 'AAA'));
        $entityA->addEntity(new TestAsset\HydratorStrategyEntityB(222, 'BBB'));

        $attributes = $this->hydrator->extract($entityA);

        $this->assertContains(111, $attributes['entities']);
        $this->assertContains(222, $attributes['entities']);
    }

    public function testHydratingObjects()
    {
        $this->hydrator->addStrategy('entities', new TestAsset\HydratorStrategy());

        $entityA = new TestAsset\HydratorStrategyEntityA();
        $entityA->addEntity(new TestAsset\HydratorStrategyEntityB(111, 'AAA'));
        $entityA->addEntity(new TestAsset\HydratorStrategyEntityB(222, 'BBB'));

        $attributes = $this->hydrator->extract($entityA);
        $attributes['entities'][] = 333;

        $this->hydrator->hydrate($attributes, $entityA);
        $entities = $entityA->getEntities();

        $this->assertCount(3, $entities);
    }

    /**
     * @dataProvider underscoreHandlingDataProvider
     */
    public function testWhenUsingUnderscoreSeparatedKeysHydratorStrategyIsAlwaysConsideredUnderscoreSeparatedToo(
        $underscoreSeparatedKeys,
        $formFieldKey
    ) {
        $hydrator = new ClassMethodsHydrator($underscoreSeparatedKeys);

        $strategy = $this->createMock(StrategyInterface::class);

        $entity = new TestAsset\ClassMethodsUnderscore();
        $value = $entity->getFooBar();

        $hydrator->addStrategy($formFieldKey, $strategy);

        $strategy
            ->expects($this->once())
            ->method('extract')
            ->with($this->identicalTo($value))
            ->will($this->returnValue($value))
        ;

        $attributes = $hydrator->extract($entity);

        $strategy
            ->expects($this->once())
            ->method('hydrate')
            ->with($this->identicalTo($value))
            ->will($this->returnValue($value))
        ;

        $hydrator->hydrate($attributes, $entity);
    }

    public function underscoreHandlingDataProvider()
    {
        return [
            [true, 'foo_bar'],
            [false, 'fooBar'],
        ];
    }

    public function testContextAwarenessExtract()
    {
        $strategy = new TestAsset\HydratorStrategyContextAware();
        $this->hydrator->addStrategy('field2', $strategy);

        $entityB = new TestAsset\HydratorStrategyEntityB('X', 'Y');
        $attributes = $this->hydrator->extract($entityB);

        $this->assertEquals($entityB, $strategy->object);
    }

    public function testContextAwarenessHydrate()
    {
        $strategy = new TestAsset\HydratorStrategyContextAware();
        $this->hydrator->addStrategy('field2', $strategy);

        $entityB = new TestAsset\HydratorStrategyEntityB('X', 'Y');
        $data = ['field1' => 'A', 'field2' => 'B'];
        $attributes = $this->hydrator->hydrate($data, $entityB);

        $this->assertEquals($data, $strategy->data);
    }
}
