<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Rules;

use RemotelyLiving\Doorkeeper\Identification;
use RemotelyLiving\Doorkeeper\RequestorInterface;

final class PipedComposite extends AbstractRule
{
    private Identification\PipedComposite $pipedComposite;

    public function __construct(string $pipedComposite)
    {
        $this->pipedComposite = new Identification\PipedComposite($pipedComposite);
    }

    public function getValue()
    {
        return $this->pipedComposite->getIdentifier();
    }

    protected function childCanBeSatisfied(RequestorInterface $requestor = null): bool
    {
        return $this->requestorHasMatchingId($requestor, $this->pipedComposite);
    }
}
