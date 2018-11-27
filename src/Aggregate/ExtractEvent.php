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
     * @var mixed[] Data being extracted from the $extractionObject
     */
    protected $extractedData = [];

    public function __construct(object $target, object $extractionObject)
    {
        parent::__construct();
        $this->target           = $target;
        $this->extractionObject = $extractionObject;
    }

    /**
     * Retrieves the object from which data is extracted
     */
    public function getExtractionObject() : object
    {
        return $this->extractionObject;
    }

    public function setExtractionObject(object $extractionObject) : void
    {
        $this->extractionObject = $extractionObject;
    }

    /**
     * Retrieves the data that has been extracted
     *
     * @return mixed[]
     */
    public function getExtractedData() : array
    {
        return $this->extractedData;
    }

    /**
     * @param mixed[] $extractedData
     */
    public function setExtractedData(array $extractedData) : void
    {
        $this->extractedData = $extractedData;
    }

    /**
     * Merge provided data with the extracted data
     *
     * @param mixed[] $additionalData
     */
    public function mergeExtractedData(array $additionalData) : void
    {
        $this->extractedData = array_merge($this->extractedData, $additionalData);
    }
}
