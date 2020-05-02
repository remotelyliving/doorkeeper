<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Tests\Unit\Identification;

use RemotelyLiving\Doorkeeper\Identification;
use PHPUnit\Framework\TestCase;

class IntegerIdTest extends TestCase
{
    /**
     * @dataProvider invalidIntegerIdProvider
     */
    public function testValidateInvalid($invalidId): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Identification\IntegerId($invalidId);
    }

    public function testValidateValid(): void
    {
        $this->assertInstanceOf(Identification\IntegerId::class, (new Identification\IntegerId(1)));
    }

    public function invalidIntegerIdProvider(): array
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
