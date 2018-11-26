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
 * Event triggered when the {@see AggregateHydrator} extracts
 * data from an object
 */
class ExtractEvent extends Event
{
    public const EVENT_EXTRACT = 'extract';

    /**
     * {@inheritDoc}
     */
    protected $name = self::EVENT_EXTRACT;

    /**
     * @var object
     */
    protected $extractionObject;

    /**
     * @var array
     */
    protected $extractedData = [];

    /**
     * @param object $target
     * @param object $extractionObject
     */
    public function __construct($target, $extractionObject)
    {
        parent::__construct();
        $this->target           = $target;
        $this->extractionObject = $extractionObject;
    }

    /**
     * Retrieves the object from which data is extracted
     *
     * @return object
     */
    public function getExtractionObject()
    {
        return $this->extractionObject;
    }

    /**
     * @param object $extractionObject
     */
    public function setExtractionObject($extractionObject) : void
    {
        $this->extractionObject = $extractionObject;
    }

    /**
     * Retrieves the data that has been extracted
     */
    public function getExtractedData() : array
    {
        return $this->extractedData;
    }

    /**
     * @param array $extractedData
     */
    public function setExtractedData(array $extractedData) : void
    {
        $this->extractedData = $extractedData;
    }

    /**
     * Merge provided data with the extracted data
     *
     * @param array $additionalData
     */
    public function mergeExtractedData(array $additionalData) : void
    {
        $this->extractedData = array_merge($this->extractedData, $additionalData);
    }
}
