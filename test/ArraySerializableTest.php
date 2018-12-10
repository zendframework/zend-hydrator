<?php
/**
 * @see       https://github.com/zendframework/zend-hydrator for the canonical source repository
 * @copyright Copyright (c) 2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace ZendTest\Hydrator;

use PHPUnit\Framework\TestCase;
use Zend\Hydrator\ArraySerializable;
use Zend\Hydrator\ArraySerializableHydrator;

class ArraySerializableTest extends TestCase
{
    public function testTriggerUserDeprecatedError()
    {
        $test = (object) ['message' => false];

        set_error_handler(function ($errno, $errstr) use ($test) {
            $test->message = $errstr;
            return true;
        }, E_USER_DEPRECATED);

        $hydrator = new ArraySerializable();
        restore_error_handler();

        $this->assertInstanceOf(ArraySerializableHydrator::class, $hydrator);
        $this->assertIsString($test->message);
        $this->assertContains('is deprecated, please use', $test->message);
    }
}
