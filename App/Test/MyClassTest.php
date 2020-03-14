<?php


namespace App\Test;


use App\MyClass\MyClass;
use PHPUnit\Framework\TestCase;

class MyClassTest extends TestCase
{
    public function testMyMethod()
    {
        $my = new MyClass();
        $this->assertEquals(16, $my->myMethod(7, 8));
    }
}