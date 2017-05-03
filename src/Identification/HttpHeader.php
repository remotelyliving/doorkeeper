<?php
namespace RemotelyLiving\Doorkeeper\Identification;

use Psr\Http\Message\RequestInterface;

class HttpHeader extends IdentificationAbstract
{
    /**
     * @inheritdoc
     */
    public function validate($string): void
    {
        return;
    }

    public static function createFromRequest(RequestInterface $request): self
    {
        return new HttpHeader($request->getHeaderLine(\RemotelyLiving\Doorkeeper\Rules\HttpHeader::HEADER_KEY));
    }
}
