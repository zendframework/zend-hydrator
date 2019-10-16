<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace ZendTest\Hydrator\Aggregate;

use ArrayObject;
use PHPUnit\Framework\TestCase;
use stdClass;
use Zend\Hydrator\Aggregate\AggregateHydrator;
use Zend\Hydrator\Aggregate\ExtractEvent;
use Zend\Hydrator\Aggregate\HydrateEvent;
use Zend\Hydrator\ArraySerializableHydrator;
use Zend\Hydrator\ClassMethodsHydrator;
use Zend\Hydrator\HydratorInterface;
use ZendTest\Hydrator\TestAsset\AggregateObject;

/**
 * Integration tests {@see AggregateHydrator}
 */
class AggregateHydratorFunctionalTest extends TestCase
{
    /**
     * @var AggregateHydrator
     */
    protected $hydrator;

    /**
     * {@inheritDoc}
     */
    protected function setUp() : void
    {
        $this->hydrator = new AggregateHydrator();
    }

    /**
     * Verifies that no interaction happens when the aggregate hydrator is empty
     */
    public function testEmptyAggregate()
    {
        $object = new ArrayObject(['zaphod' => 'beeblebrox']);

        $this->assertSame([], $this->hydrator->extract($object));
        $this->assertSame($object, $this->hydrator->hydrate(['arthur' => 'dent'], $object));

        $this->assertSame(['zaphod' => 'beeblebrox'], $object->getArrayCopy());
    }

    /**
     * @dataProvider getHydratorSet
     *
     * Verifies that using a single hydrator will have the aggregate hydrator behave like that single hydrator
     */
    public function testSingleHydratorExtraction(HydratorInterface $comparisonHydrator, $object)
    {
        $blueprint = clone $object;

        $this->hydrator->add($comparisonHydrator);

        $this->assertSame($comparisonHydrator->extract($blueprint), $this->hydrator->extract($object));
    }

    /**
     * @dataProvider getHydratorSet
     *
     * Verifies that using a single hydrator will have the aggregate hydrator behave like that single hydrator
     */
    public function testSingleHydratorHydration(HydratorInterface $comparisonHydrator, $object, $data)
    {
        $blueprint = clone $object;

        $this->hydrator->add($comparisonHydrator);

        $hydratedBlueprint = $comparisonHydrator->hydrate($data, $blueprint);
        $hydrated          = $this->hydrator->hydrate($data, $object);

        $this->assertEquals($hydratedBlueprint, $hydrated);

        if ($hydratedBlueprint === $blueprint) {
            $this->assertSame($hydrated, $object);
        }
    }

    /**
     * Verifies that multiple hydrators in an aggregate merge the extracted data
     */
    public function testExtractWithMultipleHydrators()
    {
        $this->hydrator->add(new ClassMethodsHydrator());
        $this->hydrator->add(new ArraySerializableHydrator());

        $object = new AggregateObject();

        $extracted = $this->hydrator->extract($object);

        $this->assertArrayHasKey('maintainer', $extracted);
        $this->assertArrayHasKey('president', $extracted);
        $this->assertSame('Marvin', $extracted['maintainer']);
        $this->assertSame('Zaphod', $extracted['president']);
    }

    /**
     * Verifies that multiple hydrators in an aggregate merge the extracted data
     */
    public function testHydrateWithMultipleHydrators()
    {
        $this->hydrator->add(new ClassMethodsHydrator());
        $this->hydrator->add(new ArraySerializableHydrator());

        $object = new AggregateObject();

        $this->assertSame(
            $object,
            $this->hydrator->hydrate(['maintainer' => 'Trillian', 'president' => '???'], $object)
        );

        $this->assertArrayHasKey('maintainer', $object->arrayData);
        $this->assertArrayHasKey('president', $object->arrayData);
        $this->assertSame('Trillian', $object->arrayData['maintainer']);
        $this->assertSame('???', $object->arrayData['president']);
        $this->assertSame('Trillian', $object->maintainer);
    }

    /**
     * Verifies that stopping propagation within a listener in the hydrator allows modifying how the
     * hydrator behaves
     */
    public function testStoppedPropagationInExtraction()
    {
        $object   = new ArrayObject(['president' => 'Zaphod']);
        $callback = function (ExtractEvent $event) {
            $event->setExtractedData(['Ravenous Bugblatter Beast of Traal']);
            $event->stopPropagation();
        };

        $this->hydrator->add(new ArraySerializableHydrator());
        $this->hydrator->getEventManager()->attach(ExtractEvent::EVENT_EXTRACT, $callback, 1000);

        $this->assertSame(['Ravenous Bugblatter Beast of Traal'], $this->hydrator->extract($object));
    }

    /**
     * Verifies that stopping propagation within a listener in the hydrator allows modifying how the
     * hydrator behaves
     */
    public function testStoppedPropagationInHydration()
    {
        $object        = new ArrayObject();
        $swappedObject = new stdClass();
        $callback = function (HydrateEvent $event) use ($swappedObject) {
            $event->setHydratedObject($swappedObject);
            $event->stopPropagation();
        };

        $this->hydrator->add(new ArraySerializableHydrator());
        $this->hydrator->getEventManager()->attach(HydrateEvent::EVENT_HYDRATE, $callback, 1000);

        $this->assertSame($swappedObject, $this->hydrator->hydrate(['president' => 'Zaphod'], $object));
    }

    /**
     * Data provider method
     *
     * @return array
     */
    public function getHydratorSet()
    {
        return [
            [new ArraySerializableHydrator(), new ArrayObject(['zaphod' => 'beeblebrox']), ['arthur' => 'dent']],
        ];
    }
}
