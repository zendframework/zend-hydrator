<?php


namespace Zend\Hydrator\NamingStrategy\UnderscoreNamingStrategy;

use Zend\Stdlib\StringUtils;

/**
 * @internal
 */
final class CamelCaseToUnderscoreFilter
{
    /** @var  bool */
    private $pcreUnicodeSupport;

    /**
     * @return bool
     */
    private function hasPcreUnicodeSupport(){
        if ($this->pcreUnicodeSupport === null) {
            $this->pcreUnicodeSupport = StringUtils::hasPcreUnicodeSupport();
        }
        return $this->pcreUnicodeSupport;
    }

    /**
     * @param  string $value
     * @return string
     */
    public function filter($value)
    {
        if (!is_scalar($value)) {
            return $value;
        }
        if ($this->hasPcreUnicodeSupport()) {
            $pattern     = ['#(\p{L})(\p{Nd}+)(\p{L})#',
                '#(?<=(?:\p{Lu}))(\p{Lu}\p{Ll})#',
                '#(?<=(?:\p{Ll}|\p{Nd}))(\p{Lu})#'];
            $replacement = ['\1_\2_\3','_\1', '_\1'];
        } else {
            $pattern     = ['#([A-Za-z])([0-9]+)([A-Za-z])#',
                '#(?<=(?:[A-Z]))([A-Z]+)([A-Z][a-z])#',
                '#(?<=(?:[a-z0-9]))([A-Z])#'];
            $replacement = ['\1_\2_\3', '\1_\2', '_\1'];
        }
        $filtered = preg_replace($pattern, $replacement, $value);

        if (!extension_loaded('mbstring')) {
            $lowerFunction = 'strtolower';
        } else {
            $lowerFunction = function ($value) {
                return mb_strtolower($value, 'UTF-8');
            };
        }

        return $lowerFunction($filtered);
    }
}
