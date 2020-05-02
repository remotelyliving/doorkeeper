<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Tests\Unit\Utilities;

use RemotelyLiving\Doorkeeper\Utilities;
use PHPUnit\Framework\TestCase;

class RandomizerTest extends TestCase
{
    public function testGenerateRangedRandomInt(): void
    {
        $result = (new Utilities\Randomizer())
          ->generateRangedRandomInt(1, 100);

        $this->assertGreaterThanOrEqual(1, $result);
        $this->assertLessThanOrEqual(100, $result);
    }
}
