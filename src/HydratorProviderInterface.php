<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2017 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

namespace Zend\Hydrator;

interface HydratorProviderInterface
{
    /**
     * Provide plugin manager configuration for hydrators.
     *
     * @return array
     */
    public function getHydratorConfig();
}
