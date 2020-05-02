<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Tests\Unit\Identification;

use PHPUnit\Framework\TestCase;
use RemotelyLiving\Doorkeeper\Identification;

class StringHashTest extends TestCase
{
    /**
     * @dataProvider invalidStringHashProvider
     */
    public function testValidateInvalid($invalidId): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Identification\StringHash($invalidId);
    }

    public function testValidateValid(): void
    {
        $this->assertInstanceOf(
            Identification\StringHash::class,
            (new Identification\StringHash(md5('herpderp')))
        );
    }

    public function invalidStringHashProvider(): array
    {
        return [
            [(object)[]],
            [[]],
            [-1],
            [1.1],
        ];
    }
}
