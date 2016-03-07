<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendBench\Hydrator\Asset;

class ArraySerializableObject
{
    protected $one       = 'one';
    protected $two       = 'two';
    protected $three     = 'three';
    protected $four      = 'four';
    protected $five      = 'five';
    protected $six       = 'six';
    protected $seven     = 'seven';
    protected $eight     = 'eight';
    protected $nine      = 'nine';
    protected $ten       = 'ten';
    protected $eleven    = 'eleven';
    protected $twelve    = 'twelve';
    protected $thirteen  = 'thirteen';
    protected $fourteen  = 'fourteen';
    protected $fifteen   = 'fifteen';
    protected $sixteen   = 'sixteen';
    protected $seventeen = 'seventeen';
    protected $eighteen  = 'eighteen';
    protected $nineteen  = 'nineteen';
    protected $twenty    = 'twenty';

    public function getArrayCopy()
    {
        return [
            'one' => $this->one, 'two' => $this->two, 'three' => $this->three, 'four' => $this->four,
            'five' => $this->five, 'six' => $this->six, 'seven' => $this->seven, 'eight' => $this->eight,
            'nine' => $this->nine, 'ten' => $this->ten, 'eleven' => $this->eleven, 'twelve' => $this->twelve,
            'thirteen' => $this->thirteen, 'fourteen' => $this->fourteen, 'fifteen' => $this->fifteen,
            'sixteen' => $this->sixteen, 'seventeen' => $this->seventeen, 'eighteen' => $this->eighteen,
            'nineteen' => $this->nineteen, 'twenty' => $this->twenty
        ];
    }

    public function populate(array $data)
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }
}
