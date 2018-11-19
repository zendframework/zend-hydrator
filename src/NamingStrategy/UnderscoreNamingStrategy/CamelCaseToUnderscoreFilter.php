<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2018 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

namespace Zend\Hydrator\NamingStrategy\UnderscoreNamingStrategy;

/**
 * @internal
 */
final class CamelCaseToUnderscoreFilter
{
    use StringSupportTrait;

    /**
     * @param  string $value
     * @return string
     */
    public function filter($value)
    {
        if (! is_scalar($value)) {
            return $value;
        }

        list($pattern, $replacement) = $this->getPatternAndReplacement();
        $filtered = preg_replace($pattern, $replacement, $value);

        $lowerFunction = $this->getLowerFunction();
        return $lowerFunction($filtered);
    }

    /**
     * @return string[][] Array with two elements, first the patterns, then the
     *     replacements. Each element is an array of strings.
     */
    private function getPatternAndReplacement()
    {
        return $this->hasPcreUnicodeSupport()
            ? [
                [ // pattern
                    '#(\p{L})(\p{Nd}+)(\p{L})#',
                    '#(?<=(?:\p{Lu}))(\p{Lu}\p{Ll})#',
                    '#(?<=(?:\p{Ll}|\p{Nd}))(\p{Lu})#',
                ],
                [ // replacement
                    '\1_\2_\3',
                    '_\1',
                    '_\1',
                ],
            ]
            : [
                [ // pattern
                    '#([A-Za-z])([0-9]+)([A-Za-z])#',
                    '#(?<=(?:[A-Z]))([A-Z]+)([A-Z][a-z])#',
                    '#(?<=(?:[a-z0-9]))([A-Z])#',
                ],
                [ // replacement
                    '\1_\2_\3',
                    '\1_\2',
                    '_\1',
                ],
            ];
    }

    /**
     * @return callable
     */
    private function getLowerFunction()
    {
        return $this->hasMbStringSupport()
            ? function ($value) {
                return mb_strtolower($value, 'UTF-8');
            }
            : function ($value) {
                // ignore unicode characters w/ strtolower
                return preg_replace_callback('#([A-Z])#', function ($matches) {
                    return strtolower($matches[1]);
                }, $value);
            };
    }
}
