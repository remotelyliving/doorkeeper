<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Rules;

use RemotelyLiving\Doorkeeper\Identification;
use RemotelyLiving\Doorkeeper\Requestor;

final class HttpHeader extends AbstractRule
{
    public const HEADER_KEY = 'doorkeeper';

    private Identification\HttpHeader $header;

    public function __construct(string $headerValue)
    {
        $this->header = new Identification\HttpHeader($headerValue);
    }

    public function getValue()
    {
        return $this->header->getIdentifier();
    }

    protected function childCanBeSatisfied(Requestor $requestor = null): bool
    {
        return $this->requestorHasMatchingId($requestor, $this->header);
    }
}
