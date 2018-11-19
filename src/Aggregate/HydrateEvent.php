<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Zend\Hydrator\Aggregate;

use Zend\EventManager\Event;

/**
 * Event triggered when the {@see AggregateHydrator} hydrates
 * data into an object
 */
class HydrateEvent extends Event
{
    public const EVENT_HYDRATE = 'hydrate';

    /**
     * {@inheritDoc}
     */
    protected $name = self::EVENT_HYDRATE;

    /**
     * @var object
     */
    protected $hydratedObject;

    /**
     * @var array
     */
    protected $hydrationData;

    /**
     * @param object $target
     * @param object $hydratedObject
     */
    public function __construct($target, $hydratedObject, array $hydrationData)
    {
        $this->target         = $target;
        $this->hydratedObject = $hydratedObject;
        $this->hydrationData  = $hydrationData;
    }

    /**
     * Retrieves the object that is being hydrated
     *
     * @return object
     */
    public function getHydratedObject()
    {
        return $this->hydratedObject;
    }

    /**
     * @param object $hydratedObject
     */
    public function setHydratedObject($hydratedObject) : void
    {
        $this->hydratedObject = $hydratedObject;
    }

    /**
     * Retrieves the data that is being used for hydration
     */
    public function getHydrationData() : array
    {
        return $this->hydrationData;
    }

    /**
     * @param array $hydrationData
     */
    public function setHydrationData(array $hydrationData) : void
    {
        $this->hydrationData = $hydrationData;
    }
}
