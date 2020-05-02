<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Tests\Unit\Rules;

use PHPUnit\Framework\TestCase;
use Psr\Http;
use RemotelyLiving\Doorkeeper\Requestor;
use RemotelyLiving\Doorkeeper\Rules;

class HttpHeaderTest extends TestCase
{
    public function testCanBeSatisfied(): void
    {
        $request = $this->createMock(Http\Message\RequestInterface::class);
        $request->method('getHeaderLine')
            ->with(Rules\HttpHeader::HEADER_KEY)
            ->willReturn('the header');

        $rule = new Rules\HttpHeader('the header');

        $requestor = new Requestor();

        $this->assertFalse($rule->canBeSatisfied());
        $this->assertFalse($rule->canBeSatisfied($requestor));
        $this->assertTrue($rule->canBeSatisfied($requestor->withRequest($request)));
    }

    public function testGetValue(): void
    {
        $this->assertEquals('header', (new Rules\HttpHeader('header'))->getValue());
    }
}
