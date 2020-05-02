<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Tests\Unit\Identification;

use PHPUnit\Framework\TestCase;
use RemotelyLiving\Doorkeeper\Identification;

class IpAddressTest extends TestCase
{
    /**
     * @dataProvider invalidIpAddressProvider
     */
    public function testValidateInvalid($invalidId)
    {
        $this->expectException(\InvalidArgumentException::class);
        new Identification\IpAddress($invalidId);
    }

    public function testValidateValid(): void
    {
        $this->assertInstanceOf(
            Identification\IpAddress::class,
            (new Identification\IpAddress('127.0.0.1'))
        );

        $this->assertInstanceOf(
            Identification\IpAddress::class,
            (new Identification\IpAddress('2001:0db8:85a3:0000:0000:8a2e:0370:7334'))
        );
    }

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
