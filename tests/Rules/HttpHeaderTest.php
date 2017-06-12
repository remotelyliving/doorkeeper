<?php
namespace RemotelyLiving\Doorkeeper\Tests\Rules;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use RemotelyLiving\Doorkeeper\Requestor;
use RemotelyLiving\Doorkeeper\Rules\HttpHeader;
use RemotelyLiving\Doorkeeper\Rules\TypeMapper;

class HttpHeaderTest extends TestCase
{
    /**
     * @test
     */
    public function canBeSatisfied()
    {
        $request = $this->createMock(RequestInterface::class);
        $request->method('getHeaderLine')
            ->with(\RemotelyLiving\Doorkeeper\Rules\HttpHeader::HEADER_KEY)
            ->willReturn('the header');

        $rule = new HttpHeader('the header');

        $requestor = new Requestor();

        $this->assertFalse($rule->canBeSatisfied());
        $this->assertFalse($rule->canBeSatisfied($requestor));

        $this->assertTrue($rule->canBeSatisfied($requestor->withRequest($request)));
    }

    /**
     * @test
     */
    public function getValue()
    {
        $this->assertEquals('header', (new HttpHeader('header'))->getValue());
    }
}
