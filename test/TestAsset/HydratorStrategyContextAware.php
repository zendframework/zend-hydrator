<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

declare(strict_types=1);

namespace ZendTest\Hydrator\TestAsset;

use Zend\Hydrator\Strategy\DefaultStrategy;

class HydratorStrategyContextAware extends DefaultStrategy
{
    public $object;
    public $data;

    public function extract($value, ?object $object = null)
    {
        $this->object = $object;
        return $value;
    }

    public function hydrate($value, ?array $data = null)
    {
        $this->data = $data;
        return $value;
    }
}
