<?php
namespace RemotelyLiving\Doorkeeper\Tests\Identification;

use RemotelyLiving\Doorkeeper\Identification\IntegerId;
use PHPUnit\Framework\TestCase;

class IntegerIdTest extends TestCase
{
    /**
     * @test
     * @dataProvider invalidIntegerIdProvider
     * @expectedException \InvalidArgumentException
     */
    public function validateInvalid($invalidId)
    {
        new IntegerId($invalidId);
    }

    /**
     * @test
     */
    public function validateValid()
    {
        $this->assertInstanceOf(IntegerId::class, (new IntegerId(1)));
    }

    /**
     * @return array
     */
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
