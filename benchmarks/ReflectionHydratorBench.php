<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendBench\Hydrator;

use Zend\Hydrator\Reflection;
use ZendBench\Hydrator\Asset\ClassMethodsObject;

/**
 *
 * @BeforeMethods({"classSetUp"})
 * @Revs(1000)
 * @Iterations(20)
 * @Warmup(2)
 */
class ReflectionHydratorBench
{
    /**
     * @var Reflection
     */
    protected $hydrator;

    public function classSetUp()
    {
        $this->hydrator = new Reflection();
    }

    public function benchHydratorExtractionWithTwentyProperties()
    {
        $object = new ClassMethodsObject();
        $this->hydrator->extract($object);
    }

    public function benchHydratorExtractionReusingHydratorWithTwentyProperties()
    {
        for ($i = 0 ; $i != 20 ; ++$i) {
            $object = new ClassMethodsObject();
            $this->hydrator->extract($object);
        }
    }

    public function benchHydratorHydrationWithTwentyProperties()
    {
        $object = new ClassMethodsObject();
        $data   = [
            'one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5,
            'six' => 6, 'seven' => 7, 'eight' => 8, 'nine' => 9, 'ten' => 10,
            'eleven' => 11, 'twelve' => 12, 'thirteen' => 13, 'fourteen' => 14, 'fifteen' => 15,
            'sixteen' => 16, 'seventeen' => 17, 'eighteen' => 18, 'nineteen' => 19, 'twenty' => 19
        ];

        $this->hydrator->hydrate($data, $object);
    }

    public function benchHydratorHydrationReusingHydratorWithTwentyProperties()
    {
        for ($i = 0 ; $i != 20 ; ++$i) {
            $object = new ClassMethodsObject();
            $data   = [
                'one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5,
                'six' => 6, 'seven' => 7, 'eight' => 8, 'nine' => 9, 'ten' => 10,
                'eleven' => 11, 'twelve' => 12, 'thirteen' => 13, 'fourteen' => 14, 'fifteen' => 15,
                'sixteen' => 16, 'seventeen' => 17, 'eighteen' => 18, 'nineteen' => 19, 'twenty' => 19
            ];

            $this->hydrator->hydrate($data, $object);
        }
    }
}
