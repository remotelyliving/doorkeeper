<?php
namespace RemotelyLiving\Doorkeeper\Tests\Identification;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use RemotelyLiving\Doorkeeper\Identification\HttpHeader;
use RemotelyLiving\Doorkeeper\Identification\StringHash;
use RemotelyLiving\Doorkeeper\Identification\UserId;

class UserIdTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider invalidStringHashProvider
     * @expectedException \InvalidArgumentException
     */
    public function validateInvalid($invalidId)
    {
        new UserId($invalidId);
    }

    /**
     * @test
     */
    public function validateValid()
    {
        $this->assertInstanceOf(UserId::class, (new UserId(4)));
        $this->assertInstanceOf(UserId::class, (new UserId('4dd8ab53-162c-4681-930a-62879d9e4b5f')));
    }

    /**
     * @return array
     */
    public function invalidStringHashProvider(): array
    {
        return [
            [(object)[]],
            [[]],
            ['hey jude'],
        ];
    }
}
