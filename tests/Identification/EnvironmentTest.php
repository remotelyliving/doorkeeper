<?php
namespace RemotelyLiving\Doorkeeper\Tests\Identification;

use PHPUnit\Framework\TestCase;
use RemotelyLiving\Doorkeeper\Identification\Environment;

class EnvironmentTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider invalidEnvProvider
     * @expectedException \InvalidArgumentException
     */
    public function validateInvalid($invalidEnv)
    {
        new Environment($invalidEnv);
    }

    /**
     * @test
     */
    public function validateValid()
    {
        $this->assertInstanceOf(Environment::class, new Environment('DEV'));
    }

    /**
     * @return array
     */
    public function invalidEnvProvider(): array
    {
        return [
            [(object)[]],
            [1]
        ];
    }
}
