<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2018 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Zend\Hydrator\NamingStrategy\UnderscoreNamingStrategy;

/**
 * @internal
 */
final class UnderscoreToCamelCaseFilter
{
    use StringSupportTrait;

    public function filter(string $value) : string
    {
        [$pattern, $replacement] = $this->getPatternAndReplacement(
            // a unicode safe way of converting characters to \x00\x00 notation
            preg_quote('_', '#')
        );

        $filtered = preg_replace_callback($pattern, $replacement, $value);

        $lcFirstFunction = $this->getLcFirstFunction();
        return $lcFirstFunction($filtered);
    }

    /**
     * @return array Array with two items: the pattern to match, and the
     *     callback to use for replacement.
     */
    private function getPatternAndReplacement(string $pregQuotedSeparator) : array
    {
        return $this->hasPcreUnicodeSupport()
            ? $this->getUnicodePatternAndReplacement($pregQuotedSeparator)
            : [
                // pattern
                '#(' . $pregQuotedSeparator . ')([\S]{1})#',
                // replacement
                function ($matches) {
                    return strtoupper($matches[2]);
                },
            ];
    }

    /**
     * @return array Array with two items: the pattern to match, and the
     *     callback to use for replacement.
     */
    private function getUnicodePatternAndReplacement(string $pregQuotedSeparator) : array
    {
        return $this->hasMbStringSupport()
            ? [
                // pattern
                '#(' . $pregQuotedSeparator . ')(\P{Z}{1})#u',
                // replacement
                function ($matches) {
                    return mb_strtoupper($matches[2], 'UTF-8');
                },
            ]
            : [
                // pattern
                '#(' . $pregQuotedSeparator . ')'
                    . '([^\p{Z}\p{Ll}]{1}|[a-zA-Z]{1})#u',
                // replacement
                function ($matches) {
                    return strtoupper($matches[2]);
                },
            ];
    }

    private function getLcFirstFunction() : callable
    {
        return $this->hasMbStringSupport()
            ? function ($value) {
                return mb_strtolower($value[0], 'UTF-8')
                    . substr($value, 1, strlen($value) - 1);
            }
            : 'lcfirst';
    }
}
