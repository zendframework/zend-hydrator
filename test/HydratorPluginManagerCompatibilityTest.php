<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

declare(strict_types=1);

namespace ZendTest\Hydrator;

use PHPUnit\Framework\TestCase;
use Zend\Hydrator\Exception\RuntimeException;
use Zend\Hydrator\HydratorInterface;
use Zend\Hydrator\HydratorPluginManager;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\Test\CommonPluginManagerTrait;

class HydratorPluginManagerCompatibilityTest extends TestCase
{
    use CommonPluginManagerTrait;

    protected function getPluginManager()
    {
        return new HydratorPluginManager(new ServiceManager());
    }

    protected function getV2InvalidPluginException()
    {
        // no-op
    }

    protected function getInstanceOf()
    {
        return HydratorInterface::class;
    }
}
