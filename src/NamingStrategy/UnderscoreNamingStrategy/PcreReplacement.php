<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Zend\Hydrator\NamingStrategy\UnderscoreNamingStrategy;

/**
 * Describe a PCRE pattern and a callback for providing a replacement.
 *
 * @internal
 */
class PcreReplacement
{
    /**
     * @var string
     */
    public $pattern;

    /**
     * @var callable
     */
    public $replacement;

    public function __construct(string $pattern, callable $replacement)
    {
        $this->pattern     = $pattern;
        $this->replacement = $replacement;
    }
}
