<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Rules;

use RemotelyLiving\Doorkeeper\Identification;
use RemotelyLiving\Doorkeeper\RequestorInterface;

final class StringHash extends AbstractRule
{
    private Identification\StringHash $hash;

    public function __construct(string $hash)
    {
        $this->hash = new Identification\StringHash($hash);
    }

    public function getValue()
    {
        return $this->hash->getIdentifier();
    }

    protected function childCanBeSatisfied(RequestorInterface $requestor = null): bool
    {
        return $this->requestorHasMatchingId($requestor, $this->hash);
    }
}
