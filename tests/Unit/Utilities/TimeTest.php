<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Tests\Unit\Utilities;

use PHPUnit\Framework\TestCase;
use RemotelyLiving\Doorkeeper\Utilities;

class TimeTest extends TestCase
{
    public function testGetDateTime(): void
    {
        $this->assertEquals(
            new \DateTimeImmutable('2012-12-12'),
            (new Utilities\Time())->getImmutableDateTime('2012-12-12')
        );
    }
}
