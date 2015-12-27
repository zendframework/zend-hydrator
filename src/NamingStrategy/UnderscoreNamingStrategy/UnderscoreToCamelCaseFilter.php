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
    /** @var  bool */
    private $mbStringSupport;

    /**
     * @return bool
     */
    private function hasPcreUnicodeSupport()
    {
        if ($this->pcreUnicodeSupport === null) {
            $this->pcreUnicodeSupport = StringUtils::hasPcreUnicodeSupport();
        }
        return $this->pcreUnicodeSupport;
    }

    /**
     * @return bool
     */
    private function hasMbStringSupport()
    {
        if ($this->mbStringSupport === null) {
            $this->mbStringSupport = extension_loaded('mbstring');
        }
        return $this->mbStringSupport;
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
            if (!$this->hasMbStringSupport()) {
                $pattern = '#('. $pregQuotedSeparator .')'
                    .'([^\p{Z}\p{Ll}]{1}|[a-zA-Z]{1})#u';
                $replacement = function ($matches) {
                    return strtoupper($matches[2]);
                };
            } else {
                $pattern = '#(' . $pregQuotedSeparator.')(\P{Z}{1})#u';
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


        if ($this->hasMbStringSupport()) {
            $lcFirstFunction = function ($value) {
                return mb_strtolower($value[0], 'UTF-8')
                    . substr($value, 1, strlen($value)-1);
            };
        } else {
            $lcFirstFunction = 'lcfirst';
        }

        return $lcFirstFunction($filtered);
    }
}
