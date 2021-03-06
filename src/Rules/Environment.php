<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Rules;

use RemotelyLiving\Doorkeeper\Identification;
use RemotelyLiving\Doorkeeper\RequestorInterface;

final class Environment extends AbstractRule
{
    private Identification\Environment $environment;

    public function __construct(string $environment)
    {
        $this->environment = new Identification\Environment($environment);
    }

    public function getValue()
    {
        return $this->environment->getIdentifier();
    }

    protected function childCanBeSatisfied(RequestorInterface $requestor = null): bool
    {
        return $this->requestorHasMatchingId($requestor, $this->environment);
    }
}
