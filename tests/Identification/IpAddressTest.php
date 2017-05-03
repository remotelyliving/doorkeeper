<?php
namespace RemotelyLiving\Doorkeeper\Tests\Identification;

use PHPUnit\Framework\TestCase;
use RemotelyLiving\Doorkeeper\Identification\IpAddress;

class IpAddressTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider invalidIpAddressProvider
     * @expectedException \InvalidArgumentException
     */
    public function validateInvalid($invalidId)
    {
        new IpAddress($invalidId);
    }

    /**
     * @test
     */
    public function validateValid()
    {
        $this->assertInstanceOf(IpAddress::class, (new IpAddress('127.0.0.1')));
        $this->assertInstanceOf(IpAddress::class, (new IpAddress('2001:0db8:85a3:0000:0000:8a2e:0370:7334')));
    }

    /**
     * @return array
     */
    public function invalidIpAddressProvider(): array
    {
        return [
            ['1'],
            ['boop'],
            [(object)[]],
            [[]],
            [-1]
        ];
    }
}
