<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendTest\Hydrator;

use Zend\Hydrator\Exception\RuntimeException;
use Zend\Hydrator\HydratorInterface;
use Zend\Hydrator\HydratorPluginManager;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\Test\CommonPluginManagerTrait;

class HydratorPluginManagerCompatibilityTest extends \PHPUnit_Framework_TestCase
{
    use CommonPluginManagerTrait;

    protected function getPluginManager()
    {
        return new HydratorPluginManager(new ServiceManager());
    }

    protected function getV2InvalidPluginException()
    {
        return RuntimeException::class;
    }

    protected function getInstanceOf()
    {
        return HydratorInterface::class;
    }
}
