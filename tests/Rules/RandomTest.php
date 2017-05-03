<?php
namespace RemotelyLiving\Doorkeeper\Tests\Rules;

use PHPUnit\Framework\TestCase;
use RemotelyLiving\Doorkeeper\Rules\Random;
use RemotelyLiving\Doorkeeper\Utilities\Randomizer;

class RandomTest extends TestCase
{
    /**
     * @test
     */
    public function canBeSatisfied()
    {
        $randomizer = $this->getMockBuilder(Randomizer::class)
          ->setMethods(['mtRand'])
          ->getMock();

        $rule = new Random('some id', $randomizer);

        $randomizer->method('mtRand')
          ->with(1, 100000)
          ->willReturn(10);

        $this->assertTrue($rule->canBeSatisfied());
    }
}
