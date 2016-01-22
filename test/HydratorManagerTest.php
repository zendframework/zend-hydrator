<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendTest\Hydrator;

use phpDocumentor\Reflection\DocBlock\Serializer;
use Zend\Hydrator\HydratorPluginManager;
use Zend\ServiceManager\ServiceManager;

class HydratorManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var HydratorPluginManager
     */
    protected $manager;

    public function setUp()
    {
        $this->manager = new HydratorPluginManager(new ServiceManager());
    }

    public function testRegisteringInvalidElementRaisesException()
    {
        $this->setExpectedException('Zend\Hydrator\Exception\RuntimeException');
        $this->manager->setService('test', $this);
    }

    public function testLoadingInvalidElementRaisesException()
    {
        $this->manager->setInvokableClass('test', get_class($this));
        $this->setExpectedException('Zend\Hydrator\Exception\RuntimeException');
        $this->manager->get('test');
    }
}
