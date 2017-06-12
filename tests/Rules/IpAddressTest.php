<?php
namespace RemotelyLiving\Doorkeeper\Tests\Rules;

use PHPUnit\Framework\TestCase;
use RemotelyLiving\Doorkeeper\Requestor;
use RemotelyLiving\Doorkeeper\Rules\IpAddress;
use RemotelyLiving\Doorkeeper\Rules\TypeMapper;

class IpAddressTest extends TestCase
{
    /**
     * @test
     */
    public function canBeSatisfied()
    {
        $rule      = new IpAddress('127.0.0.1');
        $requestor = new Requestor();

        $this->assertFalse($rule->canBeSatisfied());
        $this->assertFalse($rule->canBeSatisfied($requestor));

        $this->assertTrue($rule->canBeSatisfied($requestor->withIpAddress('127.0.0.1')));
    }

    /**
     * @test
     */
    public function getValue()
    {
        $this->assertEquals('0.0.0.0', (new IpAddress('0.0.0.0'))->getValue());
    }
}
