<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Zend\Hydrator\NamingStrategy;

use Zend\Hydrator\NamingStrategy\UnderscoreNamingStrategy\CamelCaseToUnderscoreFilter;
use Zend\Hydrator\NamingStrategy\UnderscoreNamingStrategy\UnderscoreToCamelCaseFilter;

class UnderscoreNamingStrategy implements NamingStrategyInterface
{
    /**
     * @var CamelCaseToUnderscoreFilter|null
     */
    private static $camelCaseToUnderscoreFilter;

    /**
     * @var UnderscoreToCamelCaseFilter|null
     */
    private static $underscoreToCamelCaseFilter;

    /**
     * Remove underscores and capitalize letters
     */
    public function hydrate(string $name, ?array $data = null) : string
    {
        return $this->getUnderscoreToCamelCaseFilter()->filter($name);
    }

    /**
     * Remove capitalized letters and prepend underscores.
     */
    public function extract(string $name, ?object $object = null) : string
    {
        return $this->getCamelCaseToUnderscoreFilter()->filter($name);
    }

    /**
     * @return UnderscoreToCamelCaseFilter
     */
    private function getUnderscoreToCamelCaseFilter() : UnderscoreToCamelCaseFilter
    {
        if (! static::$underscoreToCamelCaseFilter) {
            static::$underscoreToCamelCaseFilter = new UnderscoreToCamelCaseFilter();
        }

        return static::$underscoreToCamelCaseFilter;
    }

    /**
     * @return CamelCaseToUnderscoreFilter
     */
    private function getCamelCaseToUnderscoreFilter() : CamelCaseToUnderscoreFilter
    {
        if (! static::$camelCaseToUnderscoreFilter) {
            static::$camelCaseToUnderscoreFilter = new CamelCaseToUnderscoreFilter();
        }

        return static::$camelCaseToUnderscoreFilter;
    }
}
