<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendTest\Hydrator;

use Zend\Hydrator\NamingStrategy\NamingStrategyInterface;
use Zend\Hydrator\Strategy\StrategyInterface;
use ZendTest\Hydrator\TestAsset\SimpleEntity;

trait HydratorTestTrait
{
    public function testHydrateWithNamingStrategyAndStrategy()
    {
        $namingStrategy = $this->createMock(NamingStrategyInterface::class);
        $namingStrategy
            ->expects($this->any())
            ->method('hydrate')
            ->with($this->anything())
            ->will($this->returnValue('value'))
        ;

        $strategy = $this->createMock(StrategyInterface::class);
        $strategy
            ->expects($this->any())
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
