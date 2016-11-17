<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendTest\Hydrator;

use ZendTest\Hydrator\TestAsset\SimpleEntity;

trait HydratorTestTrait
{
    public function testHydrateWithNamingStrategyAndStrategy()
    {
        $namingStrategy = $this->getMock('Zend\Hydrator\NamingStrategy\NamingStrategyInterface');
        $namingStrategy
            ->expects($this->once())
            ->method('hydrate')
            ->with($this->anything())
            ->will($this->returnValue('value'))
        ;

        $strategy = $this->getMock('Zend\Hydrator\Strategy\StrategyInterface');
        $strategy
            ->expects($this->once())
            ->method('hydrate')
            ->with($this->anything())
            ->will($this->returnValue('hydrate'))
        ;

        $this->hydrator->setNamingStrategy($namingStrategy);
        $this->hydrator->addStrategy('value', $strategy);

        $entity = $this->hydrator->hydrate(['foo_bar_baz' => 'blub'], new SimpleEntity());
        $this->assertSame(
            'hydrate',
            $entity->getValue(),
            sprintf('Hydrator: %s', get_class($this->hydrator))
        );
    }
}
