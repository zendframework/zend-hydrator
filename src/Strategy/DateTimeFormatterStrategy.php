<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Zend\Hydrator\Strategy;

use DateTime;
use DateTimeInterface;
use DateTimeZone;

final class DateTimeFormatterStrategy implements StrategyInterface
{
    /**
     * @var string
     */
    private $format;

    /**
     * @var DateTimeZone|null
     */
    private $timezone;

    /**
     * @var bool
     */
    private $dateTimeFallback;

    /**
     * Constructor
     *
     * @param string            $format
     * @param DateTimeZone|null $timezone
     * @param bool              $dateTimeFallback try to parse with DateTime when createFromFormat fails
     */
    public function __construct($format = DateTime::RFC3339, DateTimeZone $timezone = null, $dateTimeFallback = false)
    {
        $this->format   = (string) $format;
        $this->timezone = $timezone;
        $this->dateTimeFallback = (bool) $dateTimeFallback;
    }

    /**
     * {@inheritDoc}
     *
     * Converts to date time string
     *
     * @param mixed|DateTimeInterface $value
     * @return mixed|string
     */
    public function extract($value)
    {
        if ($value instanceof DateTimeInterface) {
            return $value->format($this->format);
        }

        return $value;
    }

    /**
     * Converts date time string to DateTime instance for injecting to object
     *
     * {@inheritDoc}
     *
     * @param mixed|string $value
     * @return mixed|DateTimeInterface
     */
    public function hydrate($value)
    {
        if ($value === '' || $value === null) {
            return;
        }

        if ($this->timezone) {
            $hydrated = DateTime::createFromFormat($this->format, $value, $this->timezone);
        } else {
            $hydrated = DateTime::createFromFormat($this->format, $value);
        }

        if ($hydrated === false && $this->dateTimeFallback) {
            $hydrated = new DateTime($value, $this->timezone);
        }

        return $hydrated ?: $value;
    }
}
