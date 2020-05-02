<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Tests\Unit\Identification;

use PHPUnit\Framework\TestCase;
use RemotelyLiving\Doorkeeper\Identification;

class EnvironmentTest extends TestCase
{
    /**
     * @dataProvider invalidEnvProvider
     */
    public function testValidateInvalid($invalidEnv): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Identification\Environment($invalidEnv);
    }

    public function testValidateValid(): void
    {
        $this->assertInstanceOf(Identification\Environment::class, new Identification\Environment('DEV'));
    }

    public function testEquals(): void
    {
        $devEnv = new Identification\Environment('dev');
        $prodEnv = new Identification\Environment('prod');
        $otherDev = new Identification\Environment('dev');

        $this->assertTrue($devEnv->equals($otherDev));
        $this->assertFalse($devEnv->equals($prodEnv));
    }

    public function invalidEnvProvider(): array
    {
        return [
            [(object)[]],
            [1],
            [true],
            [false]
        ];
    }
}
