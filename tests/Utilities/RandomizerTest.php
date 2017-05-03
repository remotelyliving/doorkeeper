<?php
namespace RemotelyLiving\Doorkeeper\Tests\Utilities;

use RemotelyLiving\Doorkeeper\Utilities\Randomizer;
use PHPUnit\Framework\TestCase;

class RandomizerTest extends TestCase
{
    /**
     * @test
     */
    public function generateRangedRandomInt()
    {
        $result = (new Randomizer())->generateRangedRandomInt(1, 100);

        $this->assertGreaterThanOrEqual(1, $result);
        $this->assertLessThanOrEqual(100, $result);
    }
}
