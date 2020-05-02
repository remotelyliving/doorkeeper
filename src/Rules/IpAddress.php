<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Rules;

use RemotelyLiving\Doorkeeper\Identification;
use RemotelyLiving\Doorkeeper\Requestor;

final class IpAddress extends AbstractRule
{
    private Identification\IpAddress $ipAddress;

    public function __construct(string $ipAddress)
    {
        $this->ipAddress = new Identification\IpAddress($ipAddress);
    }

    public function getValue()
    {
        return $this->ipAddress->getIdentifier();
    }

    protected function childCanBeSatisfied(Requestor $requestor = null): bool
    {
        return $this->requestorHasMatchingId($requestor, $this->ipAddress);
    }
}
