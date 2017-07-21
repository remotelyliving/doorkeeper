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
     * @test
     */
    public function equals()
    {
        $dev_env = new Environment('dev');
        $prod_env = new Environment('prod');
        $other_dev = new Environment('dev');

        $this->assertTrue($dev_env->equals($other_dev));
        $this->assertFalse($dev_env->equals($prod_env));
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
