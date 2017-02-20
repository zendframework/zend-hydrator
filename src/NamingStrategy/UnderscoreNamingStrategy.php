<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Zend\Hydrator\NamingStrategy;

use Closure;
use Zend\Stdlib\StringUtils;

class UnderscoreNamingStrategy implements NamingStrategyInterface
{
    /**
     * @var Closure|null
     */
    protected static $camelCaseToUnderscoreFilter;

    /**
     * @var Closure|null
     */
    protected static $underscoreToStudlyCaseFilter;

    /**
     * Remove underscores and capitalize letters
     *
     * @param  string $name
     * @return string
     */
    public function hydrate($name)
    {
        $filter = $this->getUnderscoreToStudlyCaseFilter();

        return $filter($name);
    }

    /**
     * Remove capitalized letters and prepend underscores.
     *
     * @param  string $name
     * @return string
     */
    public function extract($name)
    {
        $filter = $this->getCamelCaseToUnderscoreFilter();

        return $filter($name);
    }

    /**
     * @return Closure
     */
    protected function getUnderscoreToStudlyCaseFilter()
    {
        if (static::$underscoreToStudlyCaseFilter instanceof Closure) {
            return static::$underscoreToStudlyCaseFilter;
        }

        return static::$underscoreToStudlyCaseFilter = function ($value) {
            if (!is_scalar($value) && !is_array($value)) {
                return $value;
            }

            // a unicode safe way of converting characters to \x00\x00 notation
            $pregQuotedSeparator = preg_quote('_', '#');
            if (StringUtils::hasPcreUnicodeSupport()) {
                $patterns = [
                    '#(' . $pregQuotedSeparator.')(\P{Z}{1})#u',
                    '#(^\P{Z}{1})#u',
                ];
                if (!extension_loaded('mbstring')) {
                    $replacements = [
                        function ($matches) {
                            return strtoupper($matches[2]);
                        },
                        function ($matches) {
                            return strtoupper($matches[1]);
                        },
                    ];
                } else {
                    $replacements = [
                        function ($matches) {
                            return mb_strtoupper($matches[2], 'UTF-8');
                        },
                        function ($matches) {
                            return mb_strtoupper($matches[1], 'UTF-8');
                        },
                    ];
                }
            } else {
                $patterns = [
                    '#(' . $pregQuotedSeparator.')([\S]{1})#',
                    '#(^[\S]{1})#',
                ];
                $replacements = [
                    function ($matches) {
                        return strtoupper($matches[2]);
                    },
                    function ($matches) {
                        return strtoupper($matches[1]);
                    },
                ];
            }
            $filtered = $value;
            foreach ($patterns as $index => $pattern) {
                $filtered = preg_replace_callback($pattern, $replacements[$index], $filtered);
            }

            $lowerCaseFirst = 'lcfirst';
            if (StringUtils::hasPcreUnicodeSupport() && extension_loaded('mbstring')) {
                $lowerCaseFirst = function ($value) {
                    if (0 === mb_strlen($value)) {
                        return $value;
                    }

                    return mb_strtolower(mb_substr($value, 0, 1)) . mb_substr($value, 1);
                };
            }

            return is_array($filtered) ? array_map($lowerCaseFirst, $filtered) : $lowerCaseFirst($filtered);
        };
    }

    /**
     * @return Closure
     */
    protected function getCamelCaseToUnderscoreFilter()
    {
        if (static::$camelCaseToUnderscoreFilter instanceof Closure) {
            return static::$camelCaseToUnderscoreFilter;
        }

        return static::$camelCaseToUnderscoreFilter = function ($value) {
            if (!is_scalar($value) && !is_array($value)) {
                return $value;
            }

            if (StringUtils::hasPcreUnicodeSupport()) {
                $pattern     = ['#(?<=(?:\p{Lu}))(\p{Lu}\p{Ll})#', '#(?<=(?:\p{Ll}|\p{Nd}))(\p{Lu})#'];
                $replacement = ['_' . '\1', '_' . '\1'];
            } else {
                $pattern     = ['#(?<=(?:[A-Z]))([A-Z]+)([A-Z][a-z])#', '#(?<=(?:[a-z0-9]))([A-Z])#'];
                $replacement = ['\1' . '_' . '\2', '_' . '\1'];
            }

            return strtolower(preg_replace($pattern, $replacement, $value));
        };
    }
}
