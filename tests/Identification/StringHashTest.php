<?php
namespace RemotelyLiving\Doorkeeper\Tests\Identification;

use PHPUnit\Framework\TestCase;
use RemotelyLiving\Doorkeeper\Identification\StringHash;

class StringHashTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider invalidStringHashProvider
     * @expectedException \InvalidArgumentException
     */
    public function validateInvalid($invalidId)
    {
        new StringHash($invalidId);
    }

    /**
     * @test
     */
    public function validateValid()
    {
        $this->assertInstanceOf(StringHash::class, (new StringHash(md5('herpderp'))));
    }

    /**
     * @return array
     */
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
