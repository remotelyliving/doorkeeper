<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Tests\Unit\Identification;

use PHPUnit\Framework\TestCase;
use RemotelyLiving\Doorkeeper\Identification;

class PipedCompositeTest extends TestCase
{
    /**
     * @dataProvider invalidIdProvider
     */
    public function testValidateInvalid($invalidId): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Identification\PipedComposite($invalidId);
    }

    public function testValidateValid(): void
    {
        $this->assertInstanceOf(
            Identification\PipedComposite::class,
            (new Identification\PipedComposite('boop|beep'))
        )
        ;
    }

    public function invalidIdProvider(): array
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
