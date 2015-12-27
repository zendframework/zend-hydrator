<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */


namespace Zend\Hydrator\NamingStrategy\UnderscoreNamingStrategy;

use Zend\Stdlib\StringUtils;

/**
 * @internal
 */
final class UnderscoreToCamelCaseFilter
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

        // a unicode safe way of converting characters to \x00\x00 notation
        $pregQuotedSeparator = preg_quote('_', '#');

        if ($this->hasPcreUnicodeSupport()) {
            $pattern = '#(' . $pregQuotedSeparator.')(\P{Z}{1})#u';
            if (!extension_loaded('mbstring')) {
                $replacement = function ($matches) {
                    return strtoupper($matches[2]);
                };
            } else {
                $replacement = function ($matches) {
                    return mb_strtoupper($matches[2], 'UTF-8');
                };
            }
        } else {
            $pattern = '#(' . $pregQuotedSeparator.')([\S]{1})#';
            $replacement = function ($matches) {
                    return strtoupper($matches[2]);
            };
        }

        $filtered = preg_replace_callback($pattern, $replacement, $value);


        if (extension_loaded('mbstring')) {
            $lcFirstFunction = function($value){
                return mb_strtolower($value[0], 'UTF-8')
                    . substr($value, 1, strlen($value)-1);
            };
        } else {
            $lcFirstFunction = 'lcfirst';
        }

        return $lcFirstFunction($filtered);
    }
}
