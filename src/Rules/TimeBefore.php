<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Rules;

use RemotelyLiving\Doorkeeper\RequestorInterface;
use RemotelyLiving\Doorkeeper\Utilities;

final class TimeBefore extends AbstractRule
{
    private \DateTimeImmutable $timeBefore;

    private Utilities\Time $timeUtility;

    public function __construct(string $timeBefore, Utilities\Time $timeUtility = null)
    {
        $this->timeUtility = $timeUtility ?? new Utilities\Time();
        $this->timeBefore = $this->timeUtility->getImmutableDateTime($timeBefore);
    }

    public function getValue()
    {
        return $this->timeBefore->format('Y-m-d H:i:s');
    }

    protected function childCanBeSatisfied(RequestorInterface $requestor = null): bool
    {
        return $this->timeBefore > $this->timeUtility->getImmutableDateTime('now');
    }
}
