<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Zend\Hydrator\NamingStrategy;

use Zend\Filter\FilterChain;
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
     *
     * @param  string $name
     * @return string
     */
    public function hydrate($name)
    {
        return $this->getUnderscoreToCamelCaseFilter()->filter($name);
    }

    /**
     * Remove capitalized letters and prepend underscores.
     *
     * @param  string $name
     * @return string
     */
    public function extract($name)
    {
        return $this->getCamelCaseToUnderscoreFilter()->filter($name);
    }

    /**
     * @return UnderscoreToCamelCaseFilter
     */
    private function getUnderscoreToCamelCaseFilter()
    {
        if (static::$underscoreToCamelCaseFilter === null) {
            static::$underscoreToCamelCaseFilter = new UnderscoreToCamelCaseFilter();
        }

        return static::$underscoreToCamelCaseFilter;
    }

    /**
     * @return CamelCaseToUnderscoreFilter
     */
    private function getCamelCaseToUnderscoreFilter()
    {
        if (static::$camelCaseToUnderscoreFilter === null) {
            static::$camelCaseToUnderscoreFilter = new CamelCaseToUnderscoreFilter();
        }

        return static::$camelCaseToUnderscoreFilter;
    }
}
