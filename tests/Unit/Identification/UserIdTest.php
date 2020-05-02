<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Tests\Unit\Identification;

use PHPUnit\Framework\TestCase;
use RemotelyLiving\Doorkeeper\Identification;

class UserIdTest extends TestCase
{
    /**
     * @dataProvider invalidStringHashProvider
     */
    public function testValidateInvalid($invalidId): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Identification\UserId($invalidId);
    }

    public function testValidateValid(): void
    {
        $this->assertInstanceOf(
            Identification\UserId::class,
            (new Identification\UserId('#string_id#'))
        )
        ;
        $this->assertInstanceOf(Identification\UserId::class, (new Identification\UserId(4)));
        $this->assertInstanceOf(
            Identification\UserId::class,
            (new Identification\UserId('4dd8ab53-162c-4681-930a-62879d9e4b5f'))
        );
    }

    public function invalidStringHashProvider(): array
    {
        return [
            [(object)[]],
            [[]],
            [true],
            [false],
            [1.123]
        ];
    }
}
