<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace ZendTest\Hydrator;

use Zend\Hydrator\NamingStrategy\NamingStrategyInterface;
use Zend\Hydrator\Strategy\StrategyInterface;
use ZendTest\Hydrator\TestAsset\SimpleEntity;

use function get_class;
use function sprintf;

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

    public function testExtractWithNamingStrategyAndStrategy()
    {
        $entity = new SimpleEntity();
        $entity->setValue('foo');

        $namingStrategy = $this->createMock(NamingStrategyInterface::class);
        $namingStrategy
            ->expects($this->any())
            ->method('extract')
            ->with($this->anything())
            ->will($this->returnValue('extractedName'));

        $strategy = $this->createMock(StrategyInterface::class);
        $strategy
            ->expects($this->any())
            ->method('extract')
            ->with($this->anything())
            ->will($this->returnValue('extractedValue'));

        $this->hydrator->setNamingStrategy($namingStrategy);
        $this->hydrator->addStrategy('extractedName', $strategy);

        $data = $this->hydrator->extract($entity);

        $this->assertSame(['extractedName' => 'extractedValue'], $data);
    }
}
