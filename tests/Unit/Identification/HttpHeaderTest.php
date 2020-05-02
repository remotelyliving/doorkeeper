<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Tests\Unit\Identification;

use PHPUnit\Framework\TestCase;
use Psr\Http;
use RemotelyLiving\Doorkeeper\Identification;
use RemotelyLiving\Doorkeeper\Rules;

class HttpHeaderTest extends TestCase
{
    /**
     * @dataProvider invalidStringHashProvider
     */
    public function testValidateInvalid($invalidId): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Identification\HttpHeader($invalidId);
    }

    public function validateValid(): void
    {
        $this->assertInstanceOf(
            Identification\HttpHeader::class,
            (new Identification\HttpHeader('herpderp'))
        );
    }

    public function testCreateFromRequest(): void
    {
        $request = $this->createMock(Http\Message\RequestInterface::class);
        $request->method('getHeaderLine')
            ->with(Rules\HttpHeader::HEADER_KEY)
            ->willReturn('boop');

        $this->assertInstanceOf(
            Identification\HttpHeader::class,
            Identification\HttpHeader::createFromRequest($request)
        );
    }

    public function invalidStringHashProvider(): array
    {
        return [
            [(object)[]],
        ];
    }
}
