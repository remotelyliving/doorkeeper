<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Tests\Unit\Rules;

use PHPUnit\Framework\TestCase;
use RemotelyLiving\Doorkeeper\Rules;
use RemotelyLiving\Doorkeeper\Utilities;

class TimeAfterTest extends TestCase
{
    public function testCanBeSatisfied(): void
    {
        $time = $this->getMockBuilder(Utilities\Time::class)
          ->onlyMethods(['getImmutableDateTime'])
          ->getMock();

        $time->method('getImmutableDateTime')
          ->willReturnMap([
              ['boop', new \DateTimeImmutable('2011-11-12')],
              ['now', new \DateTimeImmutable('2011-12-12')],
         ]);

        $rule = new Rules\TimeAfter('boop', $time);

        $this->assertTrue($rule->canBeSatisfied());
    }

    public function testCannotBeSatisfied(): void
    {
        $time = $this->getMockBuilder(Utilities\Time::class)
            ->onlyMethods(['getImmutableDateTime'])
            ->getMock();

        $time->method('getImmutableDateTime')
            ->willReturnMap([
                ['boop', new \DateTimeImmutable('2011-12-12')],
                ['now', new \DateTimeImmutable('2011-11-12')],
           ]);

        $rule = new Rules\TimeAfter('boop', $time);

        $this->assertFalse($rule->canBeSatisfied());
    }

    public function testGetValue(): void
    {
        $this->assertEquals('2011-12-12 00:00:00', (new Rules\TimeAfter('2011-12-12'))->getValue());
    }
}
