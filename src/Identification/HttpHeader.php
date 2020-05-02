<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Identification;

use Psr\Http;
use RemotelyLiving\Doorkeeper\Rules;

final class HttpHeader extends AbstractIdentification
{
    protected function validate($string): void
    {
    }

    public static function createFromRequest(Http\Message\RequestInterface $request): self
    {
        return new self($request->getHeaderLine(Rules\HttpHeader::HEADER_KEY));
    }
}
