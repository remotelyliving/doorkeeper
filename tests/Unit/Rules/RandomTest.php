<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Tests\Unit\Rules;

use PHPUnit\Framework\TestCase;
use RemotelyLiving\Doorkeeper\Rules;
use RemotelyLiving\Doorkeeper\Utilities;

class RandomTest extends TestCase
{
    public function testCanBeSatisfied(): void
    {
        $randomizer = $this->getMockBuilder(Utilities\Randomizer::class)
          ->onlyMethods(['mtRand'])
          ->getMock();

        $rule = new Rules\Random($randomizer);

        $randomizer->method('mtRand')
          ->with(1, 100)
          ->willReturn(10);

        $this->assertTrue($rule->canBeSatisfied());
    }

    public function testGetValue(): void
    {
        $this->assertNull((new Rules\Random())->getValue());
    }
}
