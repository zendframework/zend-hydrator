<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendTest\Hydrator;

use Interop\Container\ContainerInterface;
use Zend\Hydrator\DelegatingHydrator;
use Zend\Hydrator\DelegatingHydratorFactory;
use Zend\ServiceManager\ServiceLocatorInterface;

class DelegatingHydratorFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $prophesy = $this->prophesize(ServiceLocatorInterface::class);
        $prophesy->willImplement(ContainerInterface::class);
        $factory = new DelegatingHydratorFactory();
        $this->assertInstanceOf(
            DelegatingHydrator::class,
            $factory->createService($prophesy->reveal())
        );
    }
}
