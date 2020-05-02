<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Tests\Unit\Rules;

use PHPUnit\Framework\TestCase;
use RemotelyLiving\Doorkeeper\Requestor;
use RemotelyLiving\Doorkeeper\Rules;

class IpAddressTest extends TestCase
{
    public function testCanBeSatisfied(): void
    {
        $rule = new Rules\IpAddress('127.0.0.1');
        $requestor = new Requestor();

        $this->assertFalse($rule->canBeSatisfied());
        $this->assertFalse($rule->canBeSatisfied($requestor));

        $this->assertTrue($rule->canBeSatisfied($requestor->withIpAddress('127.0.0.1')));
    }

    public function testGetValue(): void
    {
        $this->assertEquals('0.0.0.0', (new Rules\IpAddress('0.0.0.0'))->getValue());
    }
}
