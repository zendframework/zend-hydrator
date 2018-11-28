<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2010-2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace ZendTest\Hydrator\TestAsset;

use Zend\Hydrator\Filter\FilterComposite;
use Zend\Hydrator\Filter\FilterInterface;
use Zend\Hydrator\Filter\FilterProviderInterface;
use Zend\Hydrator\Filter\MethodMatchFilter;
use Zend\Hydrator\Filter\GetFilter;

class ClassMethodsFilterProviderInterface implements FilterProviderInterface
{
    public function getBar()
    {
        return "foo";
    }

    public function getFoo()
    {
        return "bar";
    }

    public function isScalar($foo)
    {
        return false;
    }

    public function hasFooBar()
    {
        return true;
    }

    public function getServiceManager()
    {
        return "servicemanager";
    }

    public function getEventManager()
    {
        return "eventmanager";
    }

    public function getFilter() : FilterInterface
    {
        $filterComposite = new FilterComposite();

        $filterComposite->addFilter("get", new GetFilter());
        $excludes = new FilterComposite();
        $excludes->addFilter(
            "servicemanager",
            new MethodMatchFilter("getServiceManager"),
            FilterComposite::CONDITION_AND
        );
        $excludes->addFilter(
            "eventmanager",
            new MethodMatchFilter("getEventManager"),
            FilterComposite::CONDITION_AND
        );
        $filterComposite->addFilter("excludes", $excludes, FilterComposite::CONDITION_AND);

        return $filterComposite;
    }
}
