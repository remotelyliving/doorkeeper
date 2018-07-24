<?php
namespace RemotelyLiving\Doorkeeper\Identification;

use Psr\Http\Message\RequestInterface;

class HttpHeader extends IdentificationAbstract
{
    public function validate($string)
    {
        return;
    }

    public static function createFromRequest(RequestInterface $request): self
    {
        return new HttpHeader($request->getHeaderLine(\RemotelyLiving\Doorkeeper\Rules\HttpHeader::HEADER_KEY));
    }
}
