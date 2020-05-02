<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Tests\Unit\Rules;

use PHPUnit\Framework\TestCase;
use RemotelyLiving\Doorkeeper\Rules;
use RemotelyLiving\Doorkeeper\Utilities;

class PercentageTest extends TestCase
{
    public function testCanBeSatisfied(): void
    {
        $randomizer = $this->getMockBuilder(Utilities\Randomizer::class)
          ->onlyMethods(['mtRand'])
          ->getMock();

        $rule = new Rules\Percentage(5, $randomizer);

        $randomizer->method('mtRand')
          ->willReturnMap([
            [1, 100, 3],
            [1, 5, 3]
         ]);

        $this->assertTrue($rule->canBeSatisfied());
    }

    public function testCanBeSatisfied100PercentChance(): void
    {
        $rule = new Rules\Percentage(100);

        $this->assertTrue($rule->canBeSatisfied());
    }

    public function testCanBeSatisfied0PercentChance(): void
    {
        $rule = new Rules\Percentage(0);

        $this->assertFalse($rule->canBeSatisfied());
    }

    public function testInvalidPercentageOverOneHundred(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Rules\Percentage(101);
    }

    public function testGetValue(): void
    {
        $this->assertEquals(4, (new Rules\Percentage(4))->getValue());
    }
}
