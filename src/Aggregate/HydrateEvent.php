<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Zend\Hydrator\Aggregate;

use ArrayAccess;
use Zend\EventManager\Event;

/**
 * Event triggered when the {@see AggregateHydrator} hydrates
 * data into an object
 */
class HydrateEvent extends Event
{
    const EVENT_HYDRATE = 'hydrate';

    /**
     * {@inheritDoc}
     */
    protected $name = self::EVENT_HYDRATE;

    /**
     * @var object
     */
    protected $hydratedObject;

    /**
     * @var array|ArrayAccess
     */
    protected $hydrationData;

    /**
     * @param object $target
     * @param object $hydratedObject
     * @param array|ArrayAccess $hydrationData
     */
    public function __construct($target, $hydratedObject, $hydrationData)
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
    public function setHydratedObject($hydratedObject)
    {
        $this->hydratedObject = $hydratedObject;
    }

    /**
     * Retrieves the data that is being used for hydration
     *
     * @return array|ArrayAccess
     */
    public function getHydrationData()
    {
        return $this->hydrationData;
    }

    /**
     * @param array|ArrayAccess $hydrationData
     */
    public function setHydrationData($hydrationData)
    {
        $this->hydrationData = $hydrationData;
    }
}
