<?php
namespace RemotelyLiving\Doorkeeper\Tests\Identification;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use RemotelyLiving\Doorkeeper\Identification\HttpHeader;
use RemotelyLiving\Doorkeeper\Identification\StringHash;

class HttpHeaderTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider invalidStringHashProvider
     * @expectedException \InvalidArgumentException
     */
    public function validateInvalid($invalidId)
    {
        new HttpHeader($invalidId);
    }

    /**
     * @test
     */
    public function validateValid()
    {
        $this->assertInstanceOf(StringHash::class, (new StringHash(md5('herpderp'))));
    }

    /**
     * @test
     */
    public function createFromRequest()
    {
        $request = $this->createMock(RequestInterface::class);
        $request->method('getHeaderLine')
            ->with(\RemotelyLiving\Doorkeeper\Rules\HttpHeader::HEADER_KEY)
            ->willReturn('boop');

        $this->assertInstanceOf(HttpHeader::class, HttpHeader::createFromRequest($request));
    }

    /**
     * @return array
     */
    public function invalidStringHashProvider(): array
    {
        return [
            [(object)[]],
        ];
    }
}
