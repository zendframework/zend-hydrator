<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2018 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

namespace Zend\Hydrator\NamingStrategy\UnderscoreNamingStrategy;

use Zend\Stdlib\StringUtils;

/**
 * @internal
 */
trait StringSupportTrait
{
    /** @var bool */
    private $pcreUnicodeSupport;

    /** @var bool */
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
}
