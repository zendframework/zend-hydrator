<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendBench\Hydrator\Filter;

use Zend\Hydrator\Filter\FilterComposite;
use Zend\Hydrator\Filter\IsFilter;
use Zend\Hydrator\Filter\GetFilter;
use Zend\Hydrator\Filter\OptionalParametersFilter;
use Zend\Stdlib\Hydrator\Filter\HasFilter;
use ZendBench\Hydrator\Asset\FilterCompositeObject;

/**
 * @BeforeMethods({"classSetUp"})
 * @Revs(1000)
 * @Iterations(20)
 * @Warmup(2)
 */
class FilterCompositeBench
{
    /**
     * @var FilterComposite
     */
    protected $filter;

    /**
     * @var FilterCompositeObject
     */
    protected $object;

    public function classSetUp()
    {
        $this->filter = new FilterComposite([], FilterComposite::CONDITION_AND);
        $this->object = new FilterCompositeObject();

        $this->filter->addFilter('composition', new FilterComposite([
            new GetFilter(), new HasFilter(), new IsFilter()
        ]));
        $this->filter->addFilter('optional', new OptionalParametersFilter());
    }

    /**
     * @iterations 1000
     */
    public function traverseCompositeFilter()
    {
        $this->filter->accept('getOne', $this->object);
        $this->filter->accept('getTwo', $this->object);
        $this->filter->accept('isThree', $this->object);
    }
}
