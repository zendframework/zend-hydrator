<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendBench\Hydrator\NamingStrategy;

use Zend\Hydrator\NamingStrategy\UnderscoreNamingStrategy;

/**
 * @BeforeMethods({"classSetUp"})
 * @Revs(1000)
 * @Iterations(20)
 * @Warmup(2)
 */
class UnderscoreNamingStrategyBench
{
    /**
     * @var UnderscoreNamingStrategy
     */
    protected $strategy;

    public function classSetUp()
    {
        $this->strategy = new UnderscoreNamingStrategy();
    }

    public function benchExtractName()
    {
        $this->strategy->extract('firstName');
    }

    public function benchHydrateName()
    {
        $this->strategy->hydrate('first_name');
    }
}
