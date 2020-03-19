<?php

namespace App\Tests;

use App\Services\CalculatorService;
use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase
{
    public function testSomething()
    {
        $calculator = new CalculatorService();
        $result = $calculator->add(1, 9);
        $this->assertEquals(10, $result);
    }
}
