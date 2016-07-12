<?php

namespace ZendBench\Hydrator;

use Zend\Hydrator\ClassMethods;
use ZendBench\Hydrator\BenchAsset\User;

/**
 * @Revs(1000)
 * @Iterations(10)
 * @Warmup(2)
 */
class ClassMethodsBench
{
    /**
     * @var ClassMethods
     */
    private $classMethods;

    /**
     * @var User
     */
    private $user;

    /**
     * ClassMethodsBench constructor.
     */
    public function __construct()
    {
        $this->classMethods = new ClassMethods();

        $this->user = new User();
        $this->user->setName('Robyn');
        $this->user->setLastname('Hunsaker');
        $this->user->setEmail('robyn@hunsaker.com');
    }

    public function benchExtract()
    {
        $this->classMethods->extract($this->user);
    }
}
