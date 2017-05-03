<?php
namespace RemotelyLiving\Doorkeeper\Tests\Utilities;

use PHPUnit\Framework\TestCase;
use RemotelyLiving\Doorkeeper\Utilities\Time;

class TimeTest extends TestCase
{
    /**
     * @test
     */
    public function getDateTime()
    {
        $this->assertEquals(new \DateTimeImmutable('2012-12-12'), (new Time())->getImmutableDateTime('2012-12-12'));
    }
}
