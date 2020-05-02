<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Rules;

use RemotelyLiving\Doorkeeper\RequestorInterface;
use RemotelyLiving\Doorkeeper\Utilities;

class TimeAfter extends AbstractRule
{
    private \DateTimeImmutable $timeAfter;

    private Utilities\Time $timeUtility;

    public function __construct(string $timeAfter, Utilities\Time $timeUtility = null)
    {
        $this->timeUtility = $timeUtility ?? new Utilities\Time();
        $this->timeAfter  = $this->timeUtility->getImmutableDateTime($timeAfter);
    }

    public function getValue()
    {
        return $this->timeAfter->format('Y-m-d H:i:s');
    }

    protected function childCanBeSatisfied(RequestorInterface $requestor = null): bool
    {
        return $this->timeAfter < $this->timeUtility->getImmutableDateTime('now');
    }
}
